<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | tag.php                                                                  |
// |                                                                          |
// | Plugin system integration options                                        |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2009 by the following authors:                        |
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

global $_DB_table_prefix, $_TABLES;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['tag_list']      = $_DB_table_prefix . 'tag_list';
$_TABLES['tag_map']       = $_DB_table_prefix . 'tag_map';
$_TABLES['tag_badwords']  = $_DB_table_prefix . 'tag_badwords';
$_TABLES['tag_menu']      = $_DB_table_prefix . 'tag_menu';

// Plugin info

$_TAG_CONF['pi_name']           = 'tag';
$_TAG_CONF['pi_display_name']   = 'Tag';
$_TAG_CONF['pi_version']        = '0.5.4';
$_TAG_CONF['gl_version']        = '1.1.6';
$_TAG_CONF['pi_url']            = 'http://www.glfusion.org/';
?>