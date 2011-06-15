<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | badword.class.php                                                        |
// |                                                                          |
// | Filtering class                                                          |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Based on the Tag Plugin for Geeklog CMS                                  |
// | Copyright (C) 2008 by the following authors:                             |
// |                                                                          |
// | Authors: mystral-kk        - geeklog AT mystral-kk DOT net               |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

require_once '../../../lib-common.php';

/**
* Only let admin users access this page
*/
if (!SEC_hasRights('tag.admin')) {
    /**
	* Someone is trying to illegally access this page
	*/
    COM_errorLog("Someone has tried to illegally access the tag Admin page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: {$_SERVER['REMOTE_ADDR']}", 1);
    $display = COM_siteHeader()
			 . COM_startBlock(TAG_str('access_denied'))
			 . TAG_str('access_denied_msg')
			 . COM_endBlock()
			 . COM_siteFooter();
    echo $display;
    exit;
}

/**
* Main
*/
class TagBadword
{
	function TagBadword()
	{
	}

	function add()
	{
	}

	function edit()
	{
	}

	function delete()
	{
	}

	function view()
	{
		global $_CONF, $_TABLES;

		$body = '';
		$T = new Template($_CONF['path'] . 'plugins/tag/templates');
		$T->set_file('badword', 'admin_badword.thtml');
		$T->set_var('xhtml', XHTML);
		$T->set_var(
			'this_script',
			COM_buildURL($_CONF['site_admin_url'] . '/plugins/tag/index.php')
		);
		$T->set_var('lang_desc_admin_badword', TAG_str('desc_admin_badword'));
		$T->set_var('lang_add', TAG_str('add'));
		$T->set_var('lang_lbl_tag', TAG_str('lbl_tag'));
		$T->set_var('lang_delete_checked', TAG_str('delete_checked'));

		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);
		if (DB_error()) {
			return $retval . '<p>' . TAG_str('db_error') . '</p>';
		} else if (DB_numRows($result) == 0) {
			$T->set_var('msg', '<p>' . TAG_str('no_badword') . '</p>');
		} else {
			$sw = 1;

			while (($A = DB_fetchArray($result)) !== FALSE) {
				$word = TAG_escape($A['badword']);
				$body .= '<tr><td>'
					  .  '<input id="' . $word . '" name="words[]" type="checkbox" '
					  .  'value="' . $word . '"><label for="' . $word . '">'
					  .  $word . '</label></td></tr>' . LB;
				$sw = ($sw == 1) ? 2 : 1;
			}
		}

		$T->set_var('body', $body);
		$T->parse('output', 'badword');
		$retval = $T->finish($T->get_var('output'));

		return $retval;
	}

	function doAdd()
	{
		global $_TABLES;

		/**
		* Add a bad word into DB
		*/
		$word = TAG_post('word');
		$sql = "INSERT INTO {$_TABLES['tag_badwords']} (badword) "
			 . "VALUES ('" . DB_escapeString($word) . "')";
		$result = DB_query($sql);

		// Delete the bad word from list and map if it already exists
		$tag_id = TAG_getTagId($word);
		if ($tag_id !== false) {
			$sql = "DELETE FROM {$_TABLES['tag_list']} "
				 . "WHERE (tag_id = '" . DB_escapeString($tag_id) . "')";
			DB_query($sql);

			$sql = "DELETE FROM {$_TABLES['tag_map']} "
				 . "WHERE (tag_id = '" . DB_escapeString($tag_id) . "')";
			DB_query($sql);
		}

		return DB_error() ? TAG_str('add_fail') : TAG_str('add_success');
	}

	function doEdit()
	{
	}

	function doDelete()
	{
		global $_TABLES, $LANG_TAG;

		$submit = TAG_post('submit');
		if ($submit == $LANG_TAG['add']) {
			$this->doAdd();
			return;
		}

		$words = TAG_post('words');
		if (count($words) == 0) {
			return '';
		}

		/**
		* Delete a bad word from DB
		*/
		$words4db = array_map('DB_escapeString', $words);
		$words4db = "('" . implode("','", $words4db) . "')";

		$sql = "DELETE FROM {$_TABLES['tag_badwords']} "
			 . "WHERE (badword IN " . $words4db . ")";
		$result = DB_query($sql);

		/**
		* Rescan articles for the unbanned bad word
		*/
		foreach ($words as $tag) {
			TAG_rescanTag($tag);
		}

		return DB_error() ? TAG_str('delete_fail') : TAG_str('delete_success');
	}
}
?>