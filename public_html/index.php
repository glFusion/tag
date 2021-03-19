<?php
/**
* Tag Plugin for glFusion CMS
*
* Tag List
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2010-2018 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*
*  Based on the Original Work from Tag Plugin
*  @copyright  Copyright (c) 2008 mystral-kk - geeklog AT mystral-kk DOT net
*
*/

require_once '../lib-common.php';

$page = '';

/**
* Retrieve request vars
*/
COM_setArgNames(array('tag',''));
$tag = COM_getArgument('tag');

/**
* Display
*/
$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('page', 'index.thtml');

$tc = TAG_getTagCloud($_TAG_CONF['max_tag_cloud']);
if ($tc == '') {
    $T->set_var('no_tag',true);
}
$T->set_var(array(
    'lang_tag_list' => $LANG_TAG['tag_list'],
    'tag_cloud'     => $tc
));

/**
* Other tags
*/

if ( $tag == '' ) {
    // grab the highest by count
    $sql = "SELECT * FROM {$_TABLES['tag_list']} WHERE ignore_tag != 1 ORDER BY hits DESC LIMIT 1";
    $result = DB_query($sql);
    if ( DB_numRows($result) > 0 ) {
        $row = DB_fetchArray($result);
        $tag = $row['tag'];
    }
}

if ($tag != '') {
    $tag = TAG_normalize($tag);
    $tag_id = TAG_getTagId($tag);
    if ($tag_id !== false) {
        TAG_increaseHitCount($tag_id);
        $text = $tag;
        if ($_TAG_CONF['replace_underscore'] === true) {
            $text = str_replace('_', ' ', $text);
        }
        $T->set_var('selected_tag', sprintf($LANG_TAG['selected_tag'], htmlspecialchars($text, ENT_QUOTES, COM_getEncodingt())));
    }

    $T->set_var('tagged_items', ($tag != '') ? TAG_getTaggedItems($tag) : '');
}

$T->parse('output', 'page');

$page .= $T->finish($T->get_var('output'));

$display = TAG_siteHeader();
$display .= $page;
$display .= TAG_siteFooter();

echo $display;
?>
