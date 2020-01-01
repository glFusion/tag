<?php
/**
* Tag Plugin for glFusion CMS
*
* glFusion Plugin Interface
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2010-2020 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*
*  Based on the Original Work from Tag Plugin
*  @copyright  Copyright (c) 2008 mystral-kk - geeklog AT mystral-kk DOT net
*
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

/**
* Language file Include
*/
$langfile = $_CONF['path'] . 'plugins/tag/language/' . $_CONF['language'] . '.php';
$custfile = $_CONF['path'] . 'plugins/tag/language/custom/' . $_CONF['language'] . '.php';
if (file_exists($langfile)) {
    include_once $langfile;
    if (file_exists($custfile)) {
        include_once $custfile;
    }
} else {
    $langfile = $_CONF['path'].'plugins/tag/language/english_utf-8.php';
    $custfile = $_CONF['path'].'plugins/tag/language/custom/english_utf-8.php';
    include_once $langfile;
    if (file_exists($custfile)) {
        include_once $custfile;
    }
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

if ( !isset($_TAG_CONF['enable_whatsrelated'])) $_TAG_CONF['enable_whatsrelated'] = false;
if ( !isset($_TAG_CONF['whatsrelated_limit'])) $_TAG_CONF['whatsrelated_limit'] = 10;

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
    global $_CONF, $_TAG_CONF, $LANG_TAG;

    $menulabel = $LANG_TAG['admin_label'];
    $menurul   = $_CONF['site_url'] . "/tag/index.php";
    $menuitems = array();
    $menuitems[$menulabel] = $menurul;

    return $menuitems;
}

/**
* Check to see if this Plugin uses comments
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
        $pi_name   = $LANG_TAG['admin_label'];
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
        $pi_name   = $LANG_TAG['admin_label'];
        $admin_url = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
        $num_items = DB_count($_TABLES['tag_list'],'ignore_tag',0);
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


if ( $_TAG_CONF['enable_whatsrelated'] == true ) {
    function plugin_getwhatsrelated_tag( $type, $id )
    {
        global $_CONF, $_TAG_CONF, $_TABLES, $_PLUGINS;

        $retval = array();
        $tag_ids = '';
        $first = 0;
        $counter = 0;

        //  query map table for all tags assigned to this $type / $id
        $result = DB_query("SELECT m.tag_id FROM {$_TABLES['tag_map']} AS m LEFT JOIN {$_TABLES['tag_list']} AS l ON m.tag_id=l.tag_id WHERE m.type='".DB_escapeString($type)."' AND m.sid='".DB_escapeString($id)."' AND l.ignore_tag = 0");
        while ( ($row = DB_fetchArray($result)) != false ) {
            if ( $first != 0 ) $tag_ids .= ',';
            $tag_ids .= $row['tag_id'];
            $first++;
        }
        if ( $tag_ids != '' ) {
            $limitType = '';
            if ( isset($_TAG_CONF['limit_related_types']) && $_TAG_CONF['limit_related_types'] != "" ) {
                $allowed_related_types = '';
                $commaCount = 0;
                $limitArray = explode(',',$_TAG_CONF['limit_related_types']);
                foreach( $limitArray AS $ltype ) {
                    $ltype = strtolower($ltype);
                    if ( $ltype == 'article' || in_array($ltype,$_PLUGINS)) {
                        if ( $commaCount > 0 ) $allowed_related_types .= ',';
                        $allowed_related_types .= "'".COM_applyFilter($ltype)."'";
                        $commaCount++;
                    }
                }
                if ( $allowed_related_types != '' ) {
                    $limitType = " AND type in (".$allowed_related_types.") ";
                }
            }
            $sql = "SELECT distinct type,sid FROM {$_TABLES['tag_map']}
                    WHERE tag_id in (".$tag_ids.") " . $limitType . "
                    AND (sid != '".DB_escapeString($id)."')";

            $result = DB_query($sql);

            while (  ($row = DB_fetchArray($result)) != false ) {
                $itemInfo = PLG_getItemInfo($row['type'], $row['sid'], 'title,url,date-created');
                if ( isset($itemInfo['title']) && $itemInfo['title'] != '' && $itemInfo['url'] != '' ) {
                    $retval[] = array('title' => $itemInfo['title'], 'url' => $itemInfo['url'],'date' => $itemInfo['date-created']);
                    $counter++;
                }
                if ( $counter >= $_TAG_CONF['whatsrelated_limit'] ) break;
            }
        }
        usort($retval,"_itemCmp"); // sort to return newest first
        return $retval;
    }
}


function _itemCmp($a, $b)
{
    if ( $a['date'] == $b['date']) {
        return 0;
    }
    return ($a['date'] < $b['date']) ? 1 : -1;
}



/**
* Upgrade the plugin
*
*
* @return   boolean true (= success)
*
*/
function plugin_upgrade_tag()
{
    global $_CONF, $_TABLES,$_TAG_CONF;

    require_once $_CONF['path'] . 'plugins/tag/upgrade.php';

    return tag_upgrade();
}


function plugin_getheadercss_tag()
{
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
function plugin_getheadercode_tag()
{
    global $_CONF, $_USER, $_TAG_CONF;

    $retval = '';

    if ($_TAG_CONF['num_keywords'] > 0) {
        $keywords = TAG_getKeywords($_TAG_CONF['num_keywords']);
        if (count($keywords) > 0) {
            $keywords = array_map('htmlspecialchars', $keywords);
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
function plugin_enablestatechange_tag($state)
{
    global $_TABLES, $_TAG_CONF;

    $state = ($state === true) ? 1 : 0;

    $sql = "UPDATE {$_TABLES['blocks']} "
            . "SET is_enabled = '" . DB_escapeString($state) . "' "
            . "WHERE (name = '" . DB_escapeString($_TAG_CONF['default_block_name']) . "')";

    DB_query($sql);
}

/**
* Add template vars
*
*
* Vars available in templates are {tag_label} and {tag_part}.
*/
if (isset($_TAG_CONF['publish_as_template_vars']) && $_TAG_CONF['publish_as_template_vars'] == true) {
    function plugin_templatesetvars_tag($templatename, &$template) {
        global $_TAG_CONF;

        if ( isset($_TAG_CONF['template_vars']['tag_label']) &&  isset($_TAG_CONF['template_vars']['tag_part'])) {
            $template->set_var('tag_label', $_TAG_CONF['template_vars']['tag_label']);
            $template->set_var('tag_part', $_TAG_CONF['template_vars']['tag_part']);
        }
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
* Normalize a string
*/
function TAG_normalize($str)
{
    global $_TAG_CONF;

    if ($_TAG_CONF['tag_case_sensitive'] == false) {
        $str = utf8_strtolower($str);
    }
    if (utf8_strlen($str) > $_TAG_CONF['max_tag_len']) {
        $str = utf8_substr($str, 0, $_TAG_CONF['max_tag_len']);
    }
    return $str;
}

/**
* Replace [tag:foo] tags
*
*/
function plugin_autotags_tag($op, $content = '', $autotag = '') {
    global $_CONF, $_TAG_CONF, $_VARS, $LANG_TAG;

    $namespace = isset($_VARS['at_namespace']) ? $_VARS['at_namespace'] : '';
    if ($op == 'tagname') {
        if ( !isset($_TAG_CONF['tag_name'])) $_TAG_CONF['tag_name'] = 'tag';
        return array($_TAG_CONF['tag_name'],'tagcloud');
    } else if ($op == 'tagusage') {
        return;
    } else if ($op == 'desc' ) {
        $tagDescription = $LANG_TAG['desc_'.$_TAG_CONF['tag_name']];
        return $tagDescription;
    } else if ($op == 'parse') {
        if (isset($autotag['tag']) && in_array(strtolower($autotag['tag']), (array) $_TAG_CONF['tag_name'])) {
            $tagstr   = $autotag['tagstr'];
            $param    = substr($tagstr, strlen($_TAG_CONF['tag_name']) + 2, -1);
            $tag_part = '';

            if ($param != '') {
                $tags = TAG_split($param);
                if ( is_array($tags) ) {
                    foreach ($tags as $tag) {
                        $tag = TAG_normalize($tag);
                        if (TAG_checkBadword($tag) === true) {
                            $tag_part .= $LANG_TAG['badword_replace'] . ' ';
                        } else {
                            $text = $tag;
                            if ($_TAG_CONF['replace_underscore'] == true) {
                                $text = str_replace('_', ' ', $text);
                            }
                            $url = $_CONF['site_url'] . '/tag/index.php?tag='
                            . rawurlencode($tag);
                            $tag_part .= '<a class="tag_link" href="'.COM_buildURL($url).'">'.htmlspecialchars($text, ENT_QUOTES, COM_getEncodingt()).'</a> ';
                        }
                    }
                }
                $tag_part = rtrim($tag_part);

                // Set work vars to be included in templates
                $_TAG_CONF['template_vars']['tag_part']  = $tag_part;
                $_TAG_CONF['template_vars']['tag_label'] = $LANG_TAG['display_label'];

                if ($_TAG_CONF['publish_as_template_vars'] == true && $namespace != 'dokuwiki') {
                    // Replace a tag part with a null string
                    $content = str_replace($tagstr, '', $content);
                } else {
                    if ($tag_part != '') {
                        $content = str_replace($tagstr, $LANG_TAG['display_label'] . $tag_part, $content);
                    }
                }
                return $content;
            }
        } elseif ( $autotag['tag'] == 'tagcloud' ) {
            if ($autotag['parm1'] == '') {
                $param = $_TAG_CONF['max_tag_cloud'];
            } else {
                $param = (int) $autotag['parm1'];
            }
            $tg = TAG_getTagCloud($param, false);
            $content = str_replace($autotag['tagstr'], $tg, $content);
            return $content;
        }
    }
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
    $tag_id = DB_getItem($_TABLES['tag_list'],'tag_id','tag = "'.DB_escapeString($tag).'" AND ignore_tag != 1');
    if ( $tag_id == NULL ) {
        return false;
    }
    return $tag_id;
}

/**
* Return a tag name
*
* @param  int    $id - tag id
* @return string     - tag name
*/
function TAG_getTagName($id) {
    global $_TABLES;

    $tag_name = DB_getItem($_TABLES['tag_list'],'tag','tag_id = "'.DB_escapeString($id).'"');
    if ( $tag_name == NULL ) {
        return '';
    }
    return $tag_name;
}

/**
* Save a tag into tag list
*
* @param $str - a normalized tag
*/


function TAG_saveTagToList($tag) {
    global $_TABLES;

    $tag = trim($tag);

    if ( ( empty($tag) == true ) || (TAG_getTagId($tag) !== false ) || TAG_checkBadword($tag) == true ) {
        return true;
    }
    $sql = "INSERT INTO {$_TABLES['tag_list']} (tag) VALUES ('" . DB_escapeString($tag) . "')";
    $result = DB_query($sql,1);
    return (DB_error() == '');
}

/**
* Save a tag into tag map
*
* @param $str - a normalized tag
*/
function TAG_saveTagToMap($tag, $id, $type) {
    global $_TABLES;

    static $list = array();

    $tag = trim($tag);
    if (empty($tag) === true) {
        return true;
    } else {
        if (isset($list[$tag])) {
            $tag_id = $list[$tag];
        } else {
            $tag_id = DB_getItem($_TABLES['tag_list'],'tag_id','tag = "'.DB_escapeString($tag).'"');
            if ( $tag_id == NULL ) {
                return true;
            }
        }
    }

    $cnt = DB_count($_TABLES['tag_map'],array('tag_id','sid','type'), array(DB_escapeString($tag_id),DB_escapeString($id),DB_escapeString($type)));

    if ( $cnt == 0 ) {
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

    if ( !isset($_TAG_CONF['tag_name']) ) {
        return $retval;
    }

    $pattern = '|\[' . $_TAG_CONF['tag_name'] . ':([^\]]*)\]|i';
    if (preg_match_all($pattern, $str, $match, PREG_SET_ORDER)) {
        foreach ($match as $M) {
            $parts = TAG_split($M[1]);

            foreach ($parts as $part) {
                $part = TAG_normalize($part);
                if (($part !== '') AND (strlen($part) <= $_TAG_CONF['max_tag_len'])) {
                    $retval[] = $part;
                }
            }
        }

        $retval = array_unique($retval);
    }

    return $retval;
}

function plugin_itemdeleted_tag($id,$type)
{
    global $_TABLES, $_PLUGINS;

    if ($type == 'tag') return;

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

    if ($type == 'tag') return;

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
    if ( isset($info['status']) ) {
        $status = (int) $info['status'];
    } else {
        $status = 1;
    }
    if ( isset($info['raw-description']) && $status == 1) {
        $text = $info['raw-description'];
        $tags = TAG_scanTag($text);
        $tags = array_unique($tags);
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                TAG_saveTagToList($tag);
                TAG_saveTagToMap($tag, $id, $type);
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

    if ( $limit == 0 ) {
        return '';
    }

    $cacheName = 'tagcloud__';
    if ( $is_block ) {
        $cacheName = 'tagcloud_block__';
    }

    $cacheInstance = $cacheName . CACHE_security_hash() . '__' . $_CONF['theme'];

    $retval = '';
    $retval = CACHE_check_instance($cacheInstance, 0);
    if ( $retval ) {
        return $retval;
    }

    $sql = "SELECT *, COUNT(t.tag_id) AS cnt "
            . "FROM {$_TABLES['tag_map']} AS m "
            . "LEFT JOIN {$_TABLES['tag_list']} AS t "
            . "ON t.tag_id = m.tag_id WHERE t.ignore_tag != 1 "
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

    while (($A = DB_fetchArray($result)) !== FALSE) {
        $list[$A['tag']] = array('tag_id' => $A['tag_id'], 'cnt' => $A['cnt'], 'type' => $A['type']);
    }

    if (count($list) == 0) {
        return '';
    }

    ksort($list, SORT_STRING);

    $T = new Template($_CONF['path'] . 'plugins/tag/templates');
    $T->set_file('cloud','tagcloud.thtml');

    $T->set_block('cloud', 'item', 'items');

    foreach ($list as $tag => $data) {
        if ( $data['type'] != 'article' ) {
            if (!in_array($data['type'], $_PLUGINS)) {
                continue;
            }
        }
        $rel   = rawurlencode($tag);
        $title = $tag;
        if ($_TAG_CONF['replace_underscore'] == true) {
            $title = str_replace('_', ' ', $title);
        }
        $title = htmlspecialchars($title, ENT_QUOTES, COM_getEncodingt());
        $level = TAG_getLevel($data['cnt']);
        $url   = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag=' . $rel);

        $T->set_var(array(
            'level'     => $level,
            'url'       => $url,
            'rel'       => $rel,
            'title'     => $title,
        ));

        $T->parse('items', 'item',true);
    }

    $T->set_var('container_id', $is_block ? 'b_tag_cloud_container' : 'tag_cloud_container');
    $T->parse('output', 'cloud');
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
                if (TAG_checkBadword($t)) {
                    continue;
                }
                $url  = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag='
                . rawurlencode($t));
                $text = $t;
                if ($_TAG_CONF['replace_underscore'] == true) {
                    $text = str_replace('_', ' ', $text);
                }
                $text = htmlspecialchars($text, ENT_QUOTES, COM_getEncodingt());
                $retval .= '<a href="' . $url . '">' . $text . '</a>  ';
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

    $sql = "SELECT m.type, m.sid FROM {$_TABLES['tag_map']} AS m LEFT JOIN {$_TABLES['tag_list']} AS l ON m.tag_id=l.tag_id "
    . "WHERE (m.tag_id = '" . DB_escapeString(TAG_getTagId($tag)) . "' AND l.ignore_tag != 1)";
    $result = DB_query($sql);
    if (DB_error()) {
        return $retval;
    }

    $list = array();

    while (($A = DB_fetchArray($result)) !== FALSE) {
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
            $T->set_var('tag', htmlspecialchars($tag, ENT_QUOTES, COM_getEncodingt()));
            $T->set_var('related', $LANG_TAG['related']);
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

        $sql = "SELECT * FROM {$_TABLES['tag_list']} WHERE ignore_tag = 1";
        $result = DB_query($sql);

        while (($A = DB_fetchArray($result)) !== FALSE) {
            $list[] = $A['tag'];
        }

        $loaded = true;
    }
    $word = MBYTE_strtolower($word);

    return in_array($word, $list);
}



/**
* Select most frequent tags as key words
*
* @param  int             $num_keywords - number of key words to be included in <head>
* @return array of string
*/
function TAG_getKeywords($num_keywords) {
    global $_TABLES, $_CONF;

    $retval = array();

    $num_keywords = intval($num_keywords);
    if ($num_keywords < 0 || $num_keywords > 100) {
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
    . "ON m.tag_id = L.tag_id WHERE L.ignore_tag = 0 "
    . "GROUP BY m.tag_id "
    . "ORDER BY cnt DESC "
    . "LIMIT {$num_keywords}";

    $result = DB_query($sql);
    if ( $result !== false ) {
        while ( ($A = DB_fetchArray($result)) !== false ) {
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

    while (($A = DB_fetchArray($result)) !== FALSE) {
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
* Scans all types of contents supported by the Tag plugin
*/
function TAG_scanAll() {
    global $_PLUGINS, $_TABLES;

    // clear out the map table and start over
    $sql = "DELETE FROM {$_TABLES['tag_map']} ";
    DB_query($sql);

    // need to implement an ajax system here

    TAG_scanAllStories();

    foreach ($_PLUGINS as $pi_name) {
        if ( is_callable('plugin_getiteminfo_' . $pi_name) && $pi_name != 'forum') {
            $function = 'plugin_getiteminfo_' . $pi_name;
            $ret = $function("*", "id,raw-description,status");
            foreach ($ret AS $index ) {
                $status = (isset($index['status']) && $index['status'] != '') ? $index['status'] : 1;
                if ( isset($index['raw-description']) && $status == 1) {
                    $tags = TAG_scanTag($index['raw-description']);
                    foreach ($tags as $tag) {
                        TAG_saveTagToList($tag);
                        TAG_saveTagToMap($tag, $index['id'], $pi_name);
                    }
                }
            }
        }
    }
    CTL_clearCache();
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
?>
