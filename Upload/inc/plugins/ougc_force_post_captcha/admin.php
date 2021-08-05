<?php

/***************************************************************************
 *
 *	OUGC Force Post Captcha plugin (/inc/plugins/ougc_force_post_captcha/admin.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *
 *	Force specific groups to fill a captcha when posting in specific forums.
 *
 ***************************************************************************
 
****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

namespace OUGCForcePostCaptcha\Admin;

use Coingecko\Coingecko;

function _info()
{
	global $lang;

	\OUGCForcePostCaptcha\Core\load_language();

	return [
		'name'			=> 'OUGC Force Post Captcha',
		'description'	=> $lang->setting_group_ougc_force_post_captcha_desc,
		'website'		=> 'https://ougc.network',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'https://ougc.network',
		'version'		=> '1.8.0',
		'versioncode'	=> 1800,
		'compatibility'	=> '18*',
		'codename'		=> 'ougc_force_post_captcha',
		'pl'			=> [
			'version'	=> 13,
			'url'		=> 'https://community.mybb.com/mods.php?action=view&pid=573'
		]
	];
}

function _activate()
{
	global $PL, $lang, $cache;

	\OUGCForcePostCaptcha\Core\load_pluginlibrary();

	$PL->settings('ougc_force_post_captcha', $lang->setting_group_ougc_force_post_captcha, $lang->setting_group_ougc_force_post_captcha_desc, [
		'groups' => [
			'title' => $lang->setting_ougc_force_post_captcha_groups,
			'description' => $lang->setting_ougc_force_post_captcha_groups_desc,
			'optionscode' => 'groupselect',
			'value' =>	'1,2,5,7',
		],
		'forums' => [
			'title' => $lang->setting_ougc_force_post_captcha_forums,
			'description' => $lang->setting_ougc_force_post_captcha_forums_desc,
			'optionscode' => 'forumselect',
			'value' =>	-1,
		],
		'firstpost' => [
			'title' => $lang->setting_ougc_force_post_captcha_firstpost,
			'description' => $lang->setting_ougc_force_post_captcha_firstpost_desc,
			'optionscode' => 'yesno',
			'value' =>	1,
		],
		'contact' => [
			'title' => $lang->setting_ougc_force_post_captcha_contact,
			'description' => $lang->setting_ougc_force_post_captcha_contact_desc,
			'optionscode' => 'yesno',
			'value' =>	1,
		],
	]);

	// Insert/update version into cache
	$plugins = $cache->read('ougc_plugins');

	if(!$plugins)
	{
		$plugins = [];
	}

	$_info = \OUGCForcePostCaptcha\Admin\_info();

	if(!isset($plugins['forcepostcaptcha']))
	{
		$plugins['forcepostcaptcha'] = $_info['versioncode'];
	}

	include MYBB_ROOT."/inc/adminfunctions_templates.php";

	find_replace_templatesets('showthread_quickreply', '#'.preg_quote('{$captcha}').'#i', '<!--OUGC_FORCE_POST_CAPTCHA-->{$captcha}');

	/*~*~* RUN UPDATES START *~*~*/

	/*~*~* RUN UPDATES END *~*~*/

	$plugins['forcepostcaptcha'] = $_info['versioncode'];

	$cache->update('ougc_plugins', $plugins);
}

function _deactivate()
{
	include MYBB_ROOT."/inc/adminfunctions_templates.php";

	find_replace_templatesets('showthread_quickreply', "#".preg_quote('<!--OUGC_FORCE_POST_CAPTCHA-->')."#i", '', 0);
}

function _install()
{
}

function _is_installed()
{
	global $cache;

	$plugins = $cache->read('ougc_plugins');

	return isset($plugins['forcepostcaptcha']);
}

function _uninstall()
{
	global $db, $PL, $cache;

	\OUGCForcePostCaptcha\Core\load_pluginlibrary();

	$PL->settings_delete('ougc_force_post_captcha');

	// Delete version from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['forcepostcaptcha']))
	{
		unset($plugins['forcepostcaptcha']);
	}

	if(!empty($plugins))
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		$cache->delete('ougc_plugins');
	}

	$cache->delete('ougc_force_post_captcha');
}