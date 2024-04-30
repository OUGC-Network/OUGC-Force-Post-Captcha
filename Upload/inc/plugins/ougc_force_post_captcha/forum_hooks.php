<?php

/***************************************************************************
 *
 *    OUGC Force Post Captcha plugin (/inc/plugins/ougc_force_post_captcha/forum_hooks.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2020 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Force specific groups to fill a captcha when posting in specific forums.
 *
 ***************************************************************************
 ****************************************************************************
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

namespace OUGCForcePostCaptcha\ForumHooks;

use captcha;
use dataHandler;
use MyBB;

function newthread_end()
{
    global $captcha, $fid, $mybb, $ougc_hide_captcha, $ougc_post_captcha;

    if (
        $captcha ||
        !is_member($mybb->settings['ougc_force_post_captcha_groups']) ||
        !is_member($mybb->settings['ougc_force_post_captcha_forums'], ['usergroup' => $fid])
    ) {
        return;
    }

    isset($ougc_hide_captcha) || $ougc_hide_captcha = false;

    $correct = false;

    require_once MYBB_ROOT . 'inc/class_captcha.php';

    $ougc_post_captcha = new captcha(false, 'post_captcha');

    if ((!empty($mybb->input['previewpost']) || $ougc_hide_captcha == true) && $ougc_post_captcha->type == 1) {
        // If previewing a post - check their current captcha input - if correct, hide the captcha input area
        // ... but only if it's a default one, reCAPTCHA and Are You a Human must be filled in every time due to draconian limits
        if ($ougc_post_captcha->validate_captcha() == true) {
            $correct = true;

            // Generate a hidden list of items for our captcha
            $captcha = $ougc_post_captcha->build_hidden_captcha();
        }
    }

    if (!$correct) {
        if ($ougc_post_captcha->type == 1) {
            $ougc_post_captcha->build_captcha();
        } elseif (in_array($ougc_post_captcha->type, array(4, 5, 8))) {
            $ougc_post_captcha->build_recaptcha();
        } elseif (in_array($ougc_post_captcha->type, array(6, 7))) {
            $ougc_post_captcha->build_hcaptcha();
        }
    } elseif ($correct && (in_array($ougc_post_captcha->type, array(4, 5, 8)))) {
        $ougc_post_captcha->build_recaptcha();
    } elseif ($correct && (in_array($ougc_post_captcha->type, array(6, 7)))) {
        $ougc_post_captcha->build_hcaptcha();
    }

    if ($ougc_post_captcha->html) {
        $captcha = $ougc_post_captcha->html;
    }
}

function newreply_end()
{
    global $mybb;

    if ($mybb->settings['ougc_force_post_captcha_firstpost']) {
        return;
    }

    newthread_end();
}

function datahandler_post_validate_thread(dataHandler &$dh): dataHandler
{
    global $fid, $mybb, $ougc_hide_captcha, $ougc_post_captcha, $db, $json_data;

    if (
        !(in_array($mybb->input['action'], ['do_newthread', 'do_newreply'])) ||
        $mybb->request_method != 'post' ||
        ($mybb->settings['captchaimage'] && !$mybb->user['uid']) ||
        !is_member($mybb->settings['ougc_force_post_captcha_groups']) ||
        !is_member($mybb->settings['ougc_force_post_captcha_forums'], ['usergroup' => $fid])
    ) {
        return $dh;
    }

    require_once MYBB_ROOT . 'inc/class_captcha.php';

    $ougc_post_captcha = new captcha();

    if ($ougc_post_captcha->validate_captcha() == false) {
        // CAPTCHA validation failed
        foreach ($ougc_post_captcha->get_errors() as $error) {
            $dh->set_error($error);
        }
    } else {
        $ougc_hide_captcha = true;
    }

    if (
        $mybb->input['action'] == 'do_newreply' &&
        $mybb->get_input('ajax', MyBB::INPUT_INT) && $ougc_post_captcha->type == 1
    ) {
        $randomstr = random_str(5);
        $imagehash = md5(random_str(12));

        $imagearray = array(
            'imagehash' => $imagehash,
            'imagestring' => $randomstr,
            'dateline' => TIME_NOW
        );

        $db->insert_query('captcha', $imagearray);

        //header("Content-type: text/html; charset={$lang->settings['charset']}");
        $data = '';
        $data .= "<captcha>$imagehash";

        if ($ougc_hide_captcha) {
            $data .= "|$randomstr";
        }

        $data .= '</captcha>';

        //header("Content-type: application/json; charset={$lang->settings['charset']}");
        $json_data = array('data' => $data);
    }

    return $dh;
}

function datahandler_post_validate_post(dataHandler &$dh): dataHandler
{
    global $mybb;

    if (empty($mybb->settings['ougc_force_post_captcha_firstpost'])) {
        datahandler_post_validate_thread($dh);
    }

    return $dh;
}

function newthread_do_newthread_end()
{
    global $ougc_post_captcha;

    if (isset($ougc_post_captcha)) {
        $ougc_post_captcha->invalidate_captcha();
    }
}

function newreply_do_newreply_end()
{
    global $mybb;

    if ($mybb->settings['ougc_force_post_captcha_firstpost']) {
        return;
    }

    newthread_do_newthread_end();
}

function showthread_end()
{
    global $quickreply, $captcha, $mybb, $fid;

    if (
        $mybb->settings['ougc_force_post_captcha_firstpost'] ||
        !$quickreply ||
        $captcha ||
        !is_member($mybb->settings['ougc_force_post_captcha_groups']) ||
        !is_member($mybb->settings['ougc_force_post_captcha_forums'], ['usergroup' => $fid])
    ) {
        return;
    }

    require_once MYBB_ROOT . 'inc/class_captcha.php';

    $post_captcha = new captcha(true, 'post_captcha');

    if ($post_captcha->html) {
        $captcha = $post_captcha->html;
    }

    $quickreply = str_replace('<!--OUGC_FORCE_POST_CAPTCHA-->', $captcha, $quickreply);
}

function contact_do_start()
{
    global $mybb;

    if (
        $mybb->settings['ougc_force_post_captcha_contact'] &&
        $mybb->settings['captchaimage'] &&
        $mybb->user['uid'] &&
        is_member($mybb->settings['ougc_force_post_captcha_groups'])
    ) {
        global $captcha, $errors;

        $captcha = new captcha();

        if ($captcha->validate_captcha() == false) {
            foreach ($captcha->get_errors() as $error) {
                $errors[] = $error;
            }
        }
    }
}

function contact_end()
{
    global $mybb;
    global $captcha;

    if (
        $mybb->settings['ougc_force_post_captcha_contact'] &&
        !$captcha &&
        is_member($mybb->settings['ougc_force_post_captcha_groups'])
    ) {
        global $post_captcha;

        $post_captcha = new captcha(true, 'post_captcha');

        if ($post_captcha->html) {
            $captcha = $post_captcha->html;
        }
    }
}