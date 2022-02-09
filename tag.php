<?php
/**
* Tag Plugin for glFusion CMS
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

global $_DB_table_prefix, $_TABLES;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['tag_list']      = $_DB_table_prefix . 'tag_list';
$_TABLES['tag_map']       = $_DB_table_prefix . 'tag_map';
// no longer used - keep to allow smooth migrations
$_TABLES['tag_badwords']  = $_DB_table_prefix . 'tag_badwords';
$_TABLES['tag_menu']      = $_DB_table_prefix . 'tag_menu';

// Plugin info

$_TAG_CONF['pi_name']           = 'tag';
$_TAG_CONF['pi_display_name']   = 'Tag';
$_TAG_CONF['pi_version']        = '2.1.2';
$_TAG_CONF['gl_version']        = '1.6.3';
$_TAG_CONF['pi_url']            = 'https://www.glfusion.org/';
?>
