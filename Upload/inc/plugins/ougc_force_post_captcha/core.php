<?php

/***************************************************************************
 *
 *    OUGC Force Post Captcha plugin (/inc/plugins/ougc_force_post_captcha/core.php)
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

namespace OUGCForcePostCaptcha\Core;

use function OUGCForcePostCaptcha\Admin\_info;

function load_language()
{
    global $lang;

    isset($lang->setting_group_ougc_force_post_captcha) || $lang->load('ougc_force_post_captcha');
}

function load_pluginlibrary()
{
    global $PL, $lang;

    load_language();

    $_info = _info();

    if ($file_exists = file_exists(PLUGINLIBRARY)) {
        global $PL;

        $PL or require_once PLUGINLIBRARY;
    }

    if (!$file_exists || $PL->version < $_info['pl']['version']) {
        flash_message(
            $lang->sprintf($lang->ougc_force_post_captcha_pluginlibrary, $_info['pl']['url'], $_info['pl']['version']),
            'error'
        );

        admin_redirect('index.php?module=config-plugins');
    }
}

function addHooks(string $namespace)
{
    global $plugins;

    $namespaceLowercase = strtolower($namespace);
    $definedUserFunctions = get_defined_functions()['user'];

    foreach ($definedUserFunctions as $callable) {
        $namespaceWithPrefixLength = strlen($namespaceLowercase) + 1;

        if (substr($callable, 0, $namespaceWithPrefixLength) == $namespaceLowercase . '\\') {
            $hookName = substr_replace($callable, '', 0, $namespaceWithPrefixLength);

            $priority = substr($callable, -2);

            if (is_numeric(substr($hookName, -2))) {
                $hookName = substr($hookName, 0, -2);
            } else {
                $priority = 10;
            }

            $plugins->add_hook($hookName, $callable, $priority);
        }
    }
}