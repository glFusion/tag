<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | functions.inc                                                            |
// |                                                                          |
// | glFusion integration APIs                                                |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2010 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
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

/**
* Language file Include
*/
$langfile = $_CONF['path'].'plugins/tag/language/'.$_CONF['language'].'.php';

if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'].'plugins/tag/language/english_utf-8.php';
}

$_TAG_CONF = array();

/*
* Check and see if we need to load the plugin configuration
*/
require_once $_CONF['path_system'].'classes/config.class.php';
$tag_config = config::get_instance();
if ($tag_config->group_exists('tag')) {
    $temp = $tag_config->get_config('tag');
    if (is_array($temp) AND (count($temp) >= 1)) {
        $_TAG_CONF = array_merge($_TAG_CONF, $temp);
    }
}
unset($tag_config);
require_once $_CONF['path'].'plugins/tag/tag.php';

/**
* Include Porter Stemmer
*/
if ($_TAG_CONF['tag_stemming'] === true) {
	require_once $_CONF['path'].'plugins/tag/porter_stemmer.php';
}

// +---------------------------------------------------------------------------+
// | glFusion Plugin API Implementations                                       |
// +---------------------------------------------------------------------------+

/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
*
* @return    array   Associative array of menu items to be added to menu
*
*/
function plugin_getmenuitems_tag() {
	global $_CONF;

	$menulabel = TAG_str('admin_label');
	$menurul   = $_CONF['site_url'] . "/tag/index.php";
    $menuitems = array();
	$menuitems[$menulabel] = $menurul;
    return $menuitems;
}

/**
* Check to see if this Plugin uses comments
*
* glFusion is checking to see if this plugin supports comments, tell it no!
* NOTE: to support comments you must use the same date/time based ID for your
* widget.  In other words, to make primary keys for your plugin you should call
* makesid().  Comments are keyed off of that...it is a limitation on how glFusion
* does comments.
*
* @return   boolean true or false
*
*/
function plugin_commentsupport_tag() {
    return false;
}

/**
* This will put an option for the plugin in the command and control block on
* moderation.php
*
* Add the plugin name, icon and link to the command and control block in moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*
*/
function plugin_cclabel_tag() {
    global $_CONF, $LANG_TAG;

	if (SEC_hasRights('tag.admin')) {
    	$pi_name   = TAG_str('admin_label');
    	$admin_url = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
    	$icon_url  = $_CONF['site_url'] . '/tag/images/tag.png';

        return array($pi_name, $admin_url, $icon_url);
    }
}

/**
* Returns the administrative option for this plugin
*
* Adds the plugin to the Admin menu
*
*
* @return   array   Array containing (plugin name, plugin admin url, # of items in plugin or '')
*
*/
function plugin_getadminoption_tag() {
	global $_CONF, $_TABLES, $LANG_TAG;

	if (SEC_hasRights('tag.admin')) {
		$pi_name   = TAG_str('admin_label');
		$admin_url = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
		$num_items = DB_count($_TABLES['tag_list']);
		return array($pi_name, $admin_url, $num_items);
	}
}

/*
* Returns the version for this plugin
*
* @return string VersionNo
*/
function plugin_chkVersion_tag() {
	global $_TAG_CONF;

	return $_TAG_CONF['pi_version'];
}

/**
* Upgrade the plugin
*
*
* @return   boolean true (= success)
*
*/
function plugin_upgrade_tag() {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$result = DB_query("SELECT pi_version FROM {$_TABLES['plugins']} WHERE (pi_name = 'tag')");
	$A = DB_fetchArray($result);
	$pi_version = $A['pi_version'];		// Version info stored in database

    require_once $_CONF['path'] . 'system/classes/config.class.php';
    $c = config::get_instance();

	// Do upgrading
	switch ($pi_version) {
		case '0.1.0':	// 0.1.0 --> 0.2.0
			$sql = "CREATE INDEX idx_tag_list_tag ON {$_TABLES['tag_list']} (tag)";
			DB_query($sql);
			$sql = "CREATE INDEX idx_tag_map_tag_id ON {$_TABLES['tag_map']} (tag_id)";
			DB_query($sql);
			/* Fall through into the next case */

		case '0.2.0':	// 0.2.0 --> 0.3.0
			/* Fall through into the next case */

		case '0.3.0':	// 0.3.0 --> 0.3.1
		case '0.3.1':
		case '0.3.2':
            require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';
            plugin_initconfig_tag();
		case '0.4.1':
		case '0.4.2':
		case '0.4.3':
		case '0.5.0':
		case '0.5.1':
		    DB_query("UPDATE {$_TABLES['tag_map']} SET type='staticpages' WHERE type='staticpage'",1);
        case '0.5.2' :
        case '0.5.3' :
        case '0.5.4' :
            $c->add('displayblocks', $_TAG_DEFAULT['displayblocks'], 'select', 0, 0, 1, 150, true, 'tag');
		default:
			break;
	}

	// Update plugin info
	$result = DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = \"{$_TAG_CONF['pi_version']}\" WHERE (pi_name = 'tag')");
	COM_errorLog(ucfirst("tag") . " plugin was successfully updated to version {$_TAG_CONF['pi_version']}.");

	return true;
}


function plugin_getheadercss_tag() {
    global $_CONF;

    $styles = array();

    $layout_css = $_CONF['path_layout'] . '/tag.css';
    if (file_exists($layout_css)) {
        $styles[] = $layout_css;
    } else {
        $styles[] = $_CONF['path_html'] . 'tag/tag.css';
    }
    return($styles);
}

/**
* Append a link to CSS file and <meta name="keywords" content="foo,bar"> tag
* to <head> section
*/
function plugin_getheadercode_tag() {
	global $_CONF, $_USER, $_TAG_CONF;

    $retval = '';

	if ($_TAG_CONF['num_keywords'] > 0) {
		$keywords = TAG_getKeywords($_TAG_CONF['num_keywords']);
		if (count($keywords) > 0) {
			$keywords = array_map('TAG_escape', $keywords);
			$retval .= LB
					.  "\t" . '<meta name="keywords" content="'
					.  implode(',', $keywords) . '" />';
		}
	}

	return $retval;
}

/**
* Enable/Disable a default tag cloud block when tag plugin is being
* enabled/disabled
*
* @param boolean $state - true if enabling, false if disabling
*/
function plugin_enablestatechange_tag($state) {
	global $_TABLES, $_TAG_CONF;

	$state = ($state === true) ? 1 : 0;
	$sql = "UPDATE {$_TABLES['blocks']} "
		 . "SET is_enabled = '" . DB_escapeString($state) . "' "
		 . "WHERE (name = '" . DB_escapeString($_TAG_CONF['default_block_name']) . "') "
		 . "OR (name = '" . DB_escapeString($_TAG_CONF['default_block_name_menu']) . "')";
	 DB_query($sql);
}

/**
* Add template vars to 'article.thtml', 'featuredstorytext.thtml' and
* 'archivestorytext.thtml'.
*
*
* Vars available in templates are {tag_label} and {tag_part}.
*/
if ($_TAG_CONF['publish_as_template_vars'] === true) {
	function plugin_templatesetvars_tag($templatename, &$template) {
		global $_TAG_CONF;

        if (in_array($templatename, array('featuredarticle', 'article', 'archivearticle'))) {
			$template->set_var('tag_label', $_TAG_CONF['template_vars']['tag_label']);
			$template->set_var('tag_part', $_TAG_CONF['template_vars']['tag_part']);
		} else {
			$template->set_var('tag_label', '');
			$template->set_var('tag_part', '');
		}
	}
}

/**
* Strip slashes when magic_quotes_gpc is on
*/
function TAG_stripSlashes($var) {
	if (is_array($var)) {
		return array_map('TAG_stripSlashes', $var);
	} else {
		if (get_magic_quotes_gpc()) {
			$var = stripslashes($var);
		}

		return $var;
	}
}

/**
* Retrieve a Get var
*/
function TAG_get($name, $is_num = false) {
	if (isset($_GET[$name])) {
		return COM_applyFilter(TAG_stripSlashes($_GET[$name]), $is_num);
	} else {
		return false;
	}
}

/**
* Retrieve a Post var
*/
function TAG_post($name, $is_token = false, $is_num = false) {
	if (isset($_POST[$name])) {
		$var = TAG_stripSlashes($_POST[$name]);
		if ($is_token) {
			if (is_array($var)) {
				return array_map('COM_applyFilter', $var, array_fill(0, count($var), $is_num));
			} else {
				return COM_applyFilter($var, $is_num);
			}
		} else {
			return $var;
		}
	} else {
		return false;
	}
}

/**
* Escape a string for displaying in HTML
*/
function TAG_escape($str) {

	// Unescape a string
	$str = str_replace(
        array('&lt;', '&gt;',  '&auml;', '&ouml;', '&amp;', '&quot:', '&#039;'),
        array(   '<',    '>',   'ä',    'ö',    '&', '"',      "'"),
		$str
	);

	return htmlspecialchars($str, ENT_QUOTES, COM_getEncodingt());
}

/**
* Return an escaped language-specific message
*/
function TAG_str($msg) {
	global $LANG_TAG;

	if (isset($LANG_TAG[$msg])) {
		return TAG_escape($LANG_TAG[$msg]);
	} else {
		return '';
	}
}

/**
* Split a string acccoding to $LANG_TAG['tag_separators']
*/
function TAG_split($str) {
	global $LANG_TAG;

	$pattern = preg_quote($LANG_TAG['tag_separators']);
	$pattern = '[' . $pattern . ']+';
	if (function_exists('mb_split')) {
		if (function_exists('mb_regex_encoding')) {
			mb_regex_encoding(COM_getCharset());
		}
		$retval = mb_split($pattern, $str);
	} else {
		$retval = split($pattern, $str);
	}

	return $retval;
}

/**
* Stem a string using Porter Stemming algorithm
*
* @param  string $str - a normalized word
* @return string      - a stemmed word
*/
function TAG_stem($str) {
	global $_TAG_CONF;

	static $porterStemmer = null;

	$encoding = COM_getCharset();
	if (!is_object($porterStemmer)) {
		$porterStemmer = new PorterStemmer();
	}

	$retval = '';

	if (function_exists('mb_strlen')) {
		/**
		* Stem English part
		*/
		$i    = 0;
		$len  = mb_strlen($str, $encoding);
		$temp = $str;
		$str  = '';
		$word = '';

		while ($i < $len) {
			$ch = mb_substr($temp, $i, 1, $encoding);
			if (mb_strwidth($ch, $encoding) == 1) {
				$word .= $ch;
			} else {
				if ($word != '') {
					$str .= $porterStemmer->Stem($str);
					$word = '';
				} else {
					$str .= $ch;
				}
			}

			$i ++;
		}

		if ($word != '') {
			$str .= $porterStemmer->Stem($word);
		}
	} else {
		$str = $porterStemmer->Stem($str);
	}

	return $str;
}

/**
* Normalize a string
*/
function TAG_normalize($str) {
	global $_TAG_CONF;

	$encoding = COM_getCharset();

	if ($_TAG_CONF['tag_case_sensitive'] === false) {
		$str = MBYTE_strtolower($str);
	}

	if (MBYTE_strlen($_TAG_CONF['max_tag_len']) > $_TAG_CONF['max_tag_len']) {
		$str = MBYTE_substr($str, 0, $_TAG_CONF['max_tag_len']);
	}

	return $str;
}

/**
* Replace [tag:foo] tags
*
*/
function plugin_autotags_tag($cmd, $content = '', $autotag = '') {
	global $_CONF, $_TAG_CONF, $LANG_TAG;

	if ($cmd == 'tagname') {
		return $_TAG_CONF['tag_name'];
	} else if (in_array(strtolower($autotag['tag']), (array) $_TAG_CONF['tag_name'])) {
		$tagstr   = $autotag['tagstr'];
		$param    = substr($tagstr, strlen($_TAG_CONF['tag_name']) + 2, -1);
		$tag_part = '';

		if ($param != '') {
			$tags = TAG_split($param);

			foreach ($tags as $tag) {
				$tag = TAG_normalize($tag);
				if (TAG_checkBadword($tag) === true) {
					$tag_part .= TAG_str('badword_replace') . ' ';
				} else {
					if ($_TAG_CONF['tag_stemming'] === true) {
						$tag = TAG_stem($tag);
					}

					$text = $tag;
					if ($_TAG_CONF['replace_underscore'] === true) {
						$text = str_replace('_', ' ', $text);
					}
					$url = $_CONF['site_url'] . '/tag/index.php?tag='
						 . rawurlencode($tag);
					$tag_part .= '<a class="tag_link" href="' . COM_buildURL($url)
							  .  '">' .  TAG_escape($text) . '</a> ';
				}
			}

			$tag_part = rtrim($tag_part);

			// Set work vars to be included in templates
			$_TAG_CONF['template_vars']['tag_part']  = $tag_part;
			$_TAG_CONF['template_vars']['tag_label'] = $LANG_TAG['display_label'];

			if ($_TAG_CONF['publish_as_template_vars'] === true) {
				// Replace a tag part with a null string
				$content = str_replace($tagstr, '', $content);
			} else {
				if ($tag_part != '') {
					$content = str_replace($tagstr, $LANG_TAG['display_label'] . $tag_part, $content);
				}
			}
		}
	}

	return $content;
}

/**
* Return a tag id
*
* @param $str - a normalized tag
*/
function TAG_getTagId($tag) {
	global $_TABLES;
	static $list = array();

	if (isset($list[$tag])) {
		return $list[$tag];
	}

	$sql = "SELECT tag_id FROM {$_TABLES['tag_list']} "
		 . "WHERE (tag = '" . DB_escapeString($tag) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	} else if (DB_numRows($result) == 0) {
		return false;
	} else {
		$A = DB_fetcharray($result);
		$tag_id = $A['tag_id'];
		$list[$tag] = $tag_id;
		return $tag_id;
	}
}

/**
* Return a tag name
*
* @param  int    $id - tag id
* @return string     - tag name
*/
function TAG_getTagName($id) {
	global $_TABLES;

	$sql = "SELECT tag FROM {$_TABLES['tag_list']} WHERE (tag_id = '"
		 . DB_escapeString($id) . "')";
	$result = DB_query($sql);
	if (!DB_error() AND DB_numRows($result) == 1) {
		$A = DB_fetchArray($result);
		return $A['tag'];
	} else {
		return '';
	}
}

/**
* Save a tag into tag list
*
* @param $str - a normalized tag
*/
function TAG_saveTagToList($tag) {
	global $_TABLES;

	$tag = trim($tag);
	if (empty($tag) === true OR TAG_getTagId($tag) !== false) {
		return true;
	}

	$A = DB_fetchArray($result);
	if ($A['cnt'] == 0) {
		$sql = "INSERT INTO {$_TABLES['tag_list']} (tag) "
			 . "VALUES ('" . DB_escapeString($tag) . "')";
		$result = DB_query($sql);
		return (DB_error() == '');
	} else {
		return true;
	}
}

/**
* Save a tag into tag map
*
* @param $str - a normalized tag
*/
function TAG_saveTagToMap($tag, $id, $type) {
	global $_TABLES;

	$tag = trim($tag);
	if (empty($tag) === true) {
		return true;
	} else {
		$tag_id = TAG_getTagId($tag);
	}

	$sql = "SELECT COUNT(*) AS cnt FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id = '" . DB_escapeString($tag_id) . "') AND "
		 . "(sid = '" . DB_escapeString($id) . "') AND "
		 . "(type = '" . DB_escapeString($type) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	}

	$A = DB_fetchArray($result);
	if ($A['cnt'] == 0) {
		$sql = "INSERT INTO {$_TABLES['tag_map']} (tag_id, type, sid) "
			 . "VALUES ('" . DB_escapeString($tag_id) . "', '" . DB_escapeString($type)
			 . "', '" . DB_escapeString($id) . "')";
		$result = DB_query($sql);
		return (DB_error() == '');
	} else {
		return true;
	}

}

/**
* Scan a text and return an array of tags
*
* @param  string $str - a text to search
* @return array of string - normalized tag(s)
*/
function TAG_scanTag($str) {
	global $_TAG_CONF;

	$retval = array();

	$pattern = '|\[' . $_TAG_CONF['tag_name'] . ':([^\]]*)\]|i';
	if (preg_match_all($pattern, $str, $match, PREG_SET_ORDER)) {
		foreach ($match as $M) {
			$parts = TAG_split($M[1]);

			foreach ($parts as $part) {
				$part = TAG_normalize($part);
				if (TAG_checkBadword($part) === true) {
					continue;
				} else {
					if ($_TAG_CONF['tag_stemming'] === true) {
						$part = TAG_stem($part);
					}
				}

				if ($part != '' AND strlen($part) <= $_TAG_CONF['max_tag_len']) {
					$retval[] = $part;
				}
			}
		}

		$retval = array_unique($retval);
	}

	return $retval;
}

/**
* Return if a word is "bad" or not
*
* @param  string $word - a normalized word to check
* @return boolean - true = bad, false = not bad
*/
function TAG_isBadword($word) {
	global $_TABLES;

	static $badwords = null;
	static $loaded = false;

	if (!$loaded) {
		$badwords = array();

		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);
		if (!DB_error() AND DB_numRows($result) > 0) {
			while (($A = DB_fetchArray($result)) !== false) {
				$badwords[] = $A['badword'];
			}
		}

		$loaded = true;
	}

	return array_search($word, $badwords);
}

function plugin_itemdeleted_tag($id,$type)
{
	global $_TABLES, $_PLUGINS;

	// Delete old records from tag_map
	$sql = "DELETE FROM {$_TABLES['tag_map']} "
		 . "WHERE (type = '".$type."') AND (sid = '" . DB_escapeString($id) . "')";
	DB_query($sql);

    CACHE_remove_instance('tagcloud');
    CACHE_remove_instance('tagkwd');

	return false;
}

/**
* Call back function invoked when an item is being saved
*/
function plugin_itemsaved_tag($id, $type, $old_id='') {
	global $_TABLES, $_PLUGINS;

	if ( $old_id != '' ) {
	    $item_old_id = $old_id;
	} else {
	    $item_old_id = $id;
	}

	// Delete old records from tag_map
	$sql = "DELETE FROM {$_TABLES['tag_map']} "
		 . "WHERE (type = '".DB_escapeString($type)."') AND (sid = '" . DB_escapeString($item_old_id) . "')";
	DB_query($sql);

	$info = array();
	$what = 'raw-description,title,status';
    $info = PLG_getItemInfo($type, $item_old_id, $what,0);
    $status = (isset($info['status']) && $info['status'] != '') ? $info['status'] : 1;
    if ( isset($info['raw-description']) && $status == 1) {
        $text = $info['raw-description'];
    	$tags = TAG_scanTag($text);
    	$tags = array_unique($tags);
    	if (count($tags) > 0) {
    		foreach ($tags as $tag) {
    			if (!TAG_isBadword($tag)) {
    				TAG_saveTagToList($tag);
    				TAG_saveTagToMap($tag, $id, $type);
    			}
    		}
    	}
    }
    CACHE_remove_instance('tagcloud');
    CACHE_remove_instance('tagkwd');
    return false;
}

/**
* Get the level of a tag
*
* @param  int $count: number of a given tag
* @return int the corresponding level(0..9)
*/
function TAG_getLevel($count) {
	global $_TAG_CONF;

	$count = intval($count);
	for ($i = 0; $i <= 8; $i ++) {
		if ($count <= $_TAG_CONF['tag_cloud_threshold'][$i]) {
			return $i;
		}
	}

	return 9;
}

/**
* Get a tag cloud
*
* @param int     $limit: max number of clouds
* @param boolean $is_block: true  = for site block,
*                           false = for public_html/tag/index.php
*/
function TAG_getTagCloud($limit = 0, $is_block = false) {
	global $_CONF, $_TABLES, $_TAG_CONF, $_PLUGINS;

    $cacheInstance = 'tagcloud__' . CACHE_security_hash() . '__' . $_CONF['theme'];

    $retval = '';
    $retval = CACHE_check_instance($cacheInstance, 0);
    if ( $retval ) {
        return $retval;
    }

	$sql = "SELECT t.tag_id, t.tag, m.type, COUNT(t.tag_id) AS cnt "
		 . "FROM {$_TABLES['tag_map']} AS m "
		 . "LEFT JOIN {$_TABLES['tag_list']} AS t "
		 . "ON t.tag_id = m.tag_id "
		 . "GROUP BY t.tag_id "
		 . "ORDER BY cnt DESC ";
	if ($limit > 0) {
		$sql .= "LIMIT {$limit}";
	}
	$result = DB_query($sql);
	if (DB_error()) {
		return '';
	}

	$list = array();

	while (($A = DB_fetchArray($result)) !== false) {
		$list[$A['tag']] = array('tag_id' => $A['tag_id'], 'cnt' => $A['cnt'], 'type' => $A['type']);
	}

	ksort($list, SORT_STRING);

	$T = new Template($_CONF['path'] . 'plugins/tag/templates');
	$T->set_file(
		array(
			'container' => 'tagcloud_container.thtml',
			'item'      => 'tagcloud_item.thtml',
		)
	);

	$items = '';

	foreach ($list as $tag => $data) {
	    if ( $data['type'] != 'article' ) {
            if (!in_array($data['type'], $_PLUGINS)) {
                continue;
            }
        }
		$rel   = rawurlencode($tag);
		$title = $tag;
		if ($_TAG_CONF['replace_underscore'] === true) {
			$title = str_replace('_', ' ', $title);
		}
		$title = TAG_escape($title);
		$level = TAG_getLevel($data['cnt']);
		$url   = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag=' . $rel);
		$T->set_var('level', $level);
		$T->set_var('url', $url);
		$T->set_var('rel', $rel);
		$T->set_var('title', $title);
		$T->parse('output', 'item');
		$items .= $T->finish($T->get_var('output')) . $_TAG_CONF['tag_cloud_spacer'];
	}

	$T->set_var('container_id', $is_block ? 'b_tag_cloud_container' : 'tag_cloud_container');
	$T->set_var('items', $items);
	$T->parse('output', 'container');
	$retval .= $T->finish($T->get_var('output'));

    CACHE_create_instance($cacheInstance, $retval, 0);
	return $retval;
}

/**
* Return "Related tags" formatted in HTML
*/
function TAG_formatRelatedTags($all_tags, $tag) {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$retval = '';

	if ( is_array($all_tags) ) {
        sort($all_tags);

    	foreach ($all_tags as $t) {
    		if ($t != $tag) {
    			$url  = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag='
    				  . rawurlencode($t));
    			$text = $t;
    			if ($_TAG_CONF['replace_underscore'] === true) {
    				$text = str_replace('_', ' ', $text);
    			}
    			$text = TAG_escape($text);
    			$retval .= '<a href="' . $url . '" rel="' . $text . '">' . $text . '</a>  ';
    		}
    	}
    }

	return $retval;
}

/**
* Return items with a given tag
*/
function TAG_getTaggedItems($tag) {
	global $_CONF, $_TABLES, $_TAG_CONF, $_PLUGINS, $_MG_CONF, $MG_albums,
	       $LANG_TAG, $LANG33, $LANG_MG00, $LANG_STATIC;
    global $conf, $_DW_CONF;

	$retval = '';

	$sql = "SELECT type, sid FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id = '" . DB_escapeString(TAG_getTagId($tag)) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return $retval;
	}

	$list = array();

	while (($A = DB_fetchArray($result)) !== false) {
		list($type, $sid) = $A;
		if ( $type != '' && $sid != '' ) {
    		$list[] = array('type' => $type, 'sid' => $sid);
    	}
	}

	$T = new Template($_CONF['path'] . 'plugins/tag/templates');
	$T->set_file('item', 'tagged_item.thtml');

    $numTags = 0;

	foreach ($list as $L) {
	    $title = '';
	    $url   = '';
        $all_tags = '';
        $type_text = '';
        $what = 'raw-description,url,title,label,status';
        $info = PLG_getItemInfo($L['type'], $L['sid'], $what);
        $status = (isset($info['status']) && $info['status'] != '') ? $info['status'] : 1;
        if ( isset($info['raw-description']) && $status == 1) {
            $text = $info['raw-description'];
            $url  = $info['url'];
            $title = $info['title'];
            if ( isset($info['label']) && $info['label'] != '' ) {
                $type_text = $info['label'];
            } else {
                $type_text = $L['type'];
            }
            $all_tags = TAG_scanTag($text);
        }

        if ( $type_text != '' ) {
    		$T->set_var('title', $title);
    		$T->set_var('type',$type_text);
    		$T->set_var('url', $url);
    		$T->set_var('tag', TAG_escape($tag));
    		$T->set_var('related', TAG_str('related'));
    		$T->set_var('related_tags', TAG_formatRelatedTags($all_tags, $tag));
    		$T->parse('output', 'item');
    		$retval .= $T->finish($T->get_var('output'));
    		$numTags++;
    	}
	}

	if ( $numTags == 0 ) {
	    $retval = '';
	}

	return $retval;
}

/**
* Check if a word is bad.
*
* @note Check will be done ignoring $_TAG_CONF['tag_case_sensitive']
*/
function TAG_checkBadword($word) {
	global $_TABLES, $_TAG_CONF;

	static $list = null;
	static $loaded = false;

	if (!$loaded) {
		$list = array();

		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);

		while (($A = DB_fetchArray($result)) !== false) {
			$list[] = $A['badword'];
		}

		$loaded = true;
	}
    $word = MBYTE_strtolower($word);

	return in_array($word, $list);
}

/**
* Rescan a tag in articles when it is unbanned
*/
function TAG_rescanTag($tag) {
	global $_TABLES;

	$sql = "SELECT sid, introtext, bodytext FROM {$_TABLES['stories']}";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	}

	$sids = array();

	while (($A = DB_fetchArray($result)) !== false) {
		if (in_array($tag, TAG_scanTag($A['introtext']))) {
			$sids[] = $A['sid'];
			continue;
		} else if (in_array($tag, TAG_scanTag($A['bodytext']))) {
			$sids[] = $A['sid'];
		}
	}

	if (count($sids) > 0) {
		TAG_saveTagToList($tag);

		foreach ($sids as $sid) {
			TAG_saveTagToMap($tag, $sid, 'article');
		}
	}

	return true;
}

/**
* Select most frequent tags as key words
*
* @todo   some kind of caching mechanism will be useful
*
* @param  int             $num_keywords - number of key words to be included in <head>
* @return array of string
*/
function TAG_getKeywords($num_keywords) {
	global $_TABLES, $_CONF;

	$retval = array();

	$num_keywords = intval($num_keywords);
	if ($num_keywords < 0 OR $num_keywords > 100) {
		$num_keywords = 10;
	}

    $cacheInstance = 'tagkwd__' . CACHE_security_hash() . '__' . $_CONF['theme'];

    $retval = '';
    $retval = CACHE_check_instance($cacheInstance, 0);
    if ( $retval ) {
        return unserialize($retval);
    }

	$sql = "SELECT L.tag, COUNT(m.tag_id) AS cnt "
		 . "FROM {$_TABLES['tag_map']} AS m "
		 . "LEFT JOIN {$_TABLES['tag_list']} AS L "
		 . "ON m.tag_id = L.tag_id "
		 . "GROUP BY m.tag_id "
		 . "ORDER BY cnt DESC "
		 . "LIMIT {$num_keywords}";
	$result = DB_query($sql);
	if (!DB_error()) {
		while (($A = DB_fetchArray($result)) !== false) {
			$retval[] = $A['tag'];
		}
	}

    CACHE_create_instance($cacheInstance, serialize($retval), 0);
	return $retval;
}

/**
* Scan all stories for tags
*
* @note This is a very costly process, and should be used sparingly
*/
function TAG_scanAllStories() {
	global $_TABLES;

	$sql = "SELECT sid, introtext, bodytext FROM {$_TABLES['stories']}";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	}

	$tags = array();

	while (($A = DB_fetchArray($result)) !== false) {
		$tags_in_introxtext = TAG_scanTag($A['introtext']);
		$tags_in_bodytext   = TAG_scanTag($A['bodytext']);
		$temp = array_merge($tags_in_introxtext, $tags_in_bodytext);
		if (count($temp) > 0) {
			$tags[$A['sid']] = $temp;
		}
	}

	if (count($tags) > 0) {
		foreach ($tags as $sid => $tag) {
			foreach ($tag as $t) {
				TAG_saveTagToList($t);
				TAG_saveTagToMap($t, $sid, 'article');
			}
		}
	}
}

/**
* Return menu list
*
* @return array of (
*             int          menu_id,
*             string       menu_name,
*             array of int tag_ids(array of tag ids),
*             int          parent_id,
*             int          dsp_order,
*             array of int child (array of menu_ids)
*         )
*/
function TAG_getMenuList() {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$nodes = array();
	$sql = "SELECT * FROM {$_TABLES['tag_menu']} "
		 . "ORDER BY parent_id, dsp_order";
	$result = DB_query($sql);
	if (DB_error() OR DB_numRows($result) == 0) {
		return $nodes;
	}

	while (($A = DB_fetchArray($result)) !== false) {
		$A['child'] = array();

		if (trim($A['tag_ids']) == '') {
			$A['tag_ids'] = array();
		} else {
			$A['tag_ids'] = explode(',', $A['tag_ids']);
		}
		if ($A['parent_id'] != 0) {
			$nodes[$A['parent_id']]['child'][] = $A['menu_id'];
		}
		$nodes[$A['menu_id']] = $A;
	}

	return $nodes;
}

/**
* Increase a hit count of a tag
*/
function TAG_increaseHitCount($tag_id) {
	global $_TABLES;

	$sql = "UPDATE {$_TABLES['tag_list']} SET hits = hits + 1 "
		 . "WHERE (tag_id = '" . DB_escapeString($tag_id) . "')";
	DB_query($sql);
}

/**
* Return the title of a story
*/
function TAG_getStoryTitle($sid) {
	global $_TABLES;

	$sql = "SELECT title FROM {$_TABLES['stories']} "
		 . "WHERE (sid = '" . DB_escapeString($sid) . "') "
		 . COM_getPermSQL(' AND ');
	$result = DB_query($sql);
	if (!DB_error() AND DB_numRows($result) == 1) {
		$A = DB_fetchArray($result);
		return $A['title'];
	} else {
		return '';
	}
}

/**
* Return the number of items which include all the tag ids
*
* @param  array of int $tag_ids - array of tag ids
* @return int                   - the number of items
*/
function TAG_getNumItems($tag_ids) {
	global $_TABLES;

	$sql = "SELECT COUNT(sid) AS cnt "
		 . "FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id IN ('" . implode("','", array_map('DB_escapeString', $tag_ids))
		 . "')) "
		 . "GROUP BY sid "
		 . "HAVING cnt = " . count($tag_ids);
	$result = DB_query($sql);
	if (DB_error()) {
		return 'N/A';
	} else {
		return DB_numRows($result);
	}
}

/**
* Build a tag menu
*
* @access private
*/
function TAG_buildTagMenu(&$nodes, &$node, $parent_tag_ids, $level) {
	global $_CONF, $_TAG_CONF;

	$retval = '';

	$tag_ids = array_merge($parent_tag_ids, $node['tag_ids']);
	$tag_ids = array_unique($tag_ids);
	sort($tag_ids);
	$tag_ids4url = implode('_', $tag_ids);
	$url  = $_CONF['site_url'] . '/tag/menu.php?tag=' . $tag_ids4url;
	$text = $node['menu_name'];
	if ($_TAG_CONF['replace_underscore'] === true) {
		$text = str_replace('_', ' ', $text);
	}
	$retval .= str_repeat($_TAG_CONF['menu_indenter'], $level)
			.  '<a class="bl_tag_menu_item" href="' . COM_buildURL($url) . '">'
			. TAG_escape($text) . '</a>';

	// Add the number of items
	if ($_TAG_CONF['add_num_items_to_menu'] === true) {
		$retval .= ' (' . COM_numberFormat(TAG_getNumItems($tag_ids)) . ')';
	}

	$retval .= '<br />' . LB;

	// Build child items recursively
	foreach ($node['child'] as $child) {
		$retval .= TAG_buildTagMenu($nodes, $nodes[$child], $tag_ids, $level + 1);
	}

	return $retval;
}

function TAG_siteHeader($title='', $meta='')
{
    global $_TAG_CONF;

    $retval = '';

    switch( $_TAG_CONF['displayblocks'] ) {
        case 0 : // left only
        case 2 :
            $retval .= COM_siteHeader('menu',$title,$meta);
            break;
        case 1 : // right only
        case 3 :
            $retval .= COM_siteHeader('none',$title,$meta);
            break;
        default :
            $retval .= COM_siteHeader('menu',$title,$meta);
            break;
    }
    return $retval;
}

function TAG_siteFooter() {
    global $_CONF, $_TAG_CONF;

    $retval = '';

    switch( $_TAG_CONF['displayblocks'] ) {
        case 0 : // left only
        case 3 : // none
            $retval .= COM_siteFooter();
            break;
        case 1 : // right only
        case 2 : // left and right
            $retval .= COM_siteFooter( true );
            break;
        default :
            $retval .= COM_siteFooter();
            break;
    }
    return $retval;
}

/**
* Display a tag cloud in the site block
*
* @access public
* @param int     $limit: max number of tags to be displayed
* @return string HTML
*/
function phpblock_tag_cloud($limit = 0) {
	global $_CONF, $_TABLES, $_TAG_CONF;

	// Check the max number of clouds
	$limit = (int) $limit;
	if ($limit <= 0) {
		$limit = $_TAG_CONF['max_tag_cloud_in_block'];
	}

	return TAG_getTagCloud($limit, true);
}

/**
* Display a menu using tags
*
* @access public
*/
function phpblock_tag_menu() {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$nodes  = TAG_getMenuList();
	if (count($nodes) == 0) {
		return '';
	}

	$states_index = 0;
	$retval = '<div class="bl_tag_menu">' . LB;

	foreach ($nodes as $node) {
		if ($node['parent_id'] > 0) {
			break;
		}
		$retval .= TAG_buildTagMenu($nodes, $node, array(), 0);
	}

	$retval .= '</div>' . LB;

	return $retval;
}
?>