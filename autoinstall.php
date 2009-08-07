<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | autoinstall.php                                                          |
// |                                                                          |
// | glFusion Auto Installer module                                           |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009 by the following authors:                             |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
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

global $_DB_dbms;

require_once $_CONF['path'].'plugins/tag/functions.inc';
require_once $_CONF['path'].'plugins/tag/tag.php';
require_once $_CONF['path'].'plugins/tag/sql/'.$_DB_dbms.'_install.php';

// +--------------------------------------------------------------------------+
// | Plugin installation options                                              |
// +--------------------------------------------------------------------------+

$INSTALL_plugin['tag'] = array(
  'installer' => array('type' => 'installer', 'version' => '1', 'mode' => 'install'),

  'plugin' => array('type' => 'plugin', 'name' => $_TAG_CONF['pi_name'],
        'ver' => $_TAG_CONF['pi_version'], 'gl_ver' => $_TAG_CONF['gl_version'],
        'url' => $_TAG_CONF['pi_url'], 'display' => $_TAG_CONF['pi_display_name']),

  array('type' => 'table', 'table' => $_TABLES['tag_list'], 'sql' => $_SQL['tag_list']),

  array('type' => 'table', 'table' => $_TABLES['tag_map'], 'sql' => $_SQL['tag_map']),

  array('type' => 'table', 'table' => $_TABLES['tag_badwords'], 'sql' => $_SQL['tag_badwords']),

  array('type' => 'table', 'table' => $_TABLES['tag_menu'], 'sql' => $_SQL['tag_menu']),

  array('type' => 'group', 'group' => 'tag Admin', 'desc' => 'Users in this group can administer the tag plugin',
        'variable' => 'admin_group_id', 'addroot' => true),

  array('type' => 'feature', 'feature' => 'tag.admin', 'desc' => 'Ability to administer the tag plugin',
        'variable' => 'admin_feature_id'),

  array('type' => 'mapping', 'group' => 'admin_group_id', 'feature' => 'admin_feature_id',
        'log' => 'Adding Tag feature to the Tag admin group'),

  array('type' => 'sql', 'sql' => $_SQL['tag_list_index']),

  array('type' => 'sql', 'sql' => $_SQL['tag_map_index']),

  array('type' => 'block', 'name' => 'block_tag', 'title' => 'Popular tags at this site',
          'phpblockfn' => 'phpblock_tag_cloud', 'block_type' => 'phpblock',
          'group_id' => 'admin_group_id'),

  array('type' => 'block', 'name' => 'block_tag_menu', 'title' => 'Tag Menu',
          'phpblockfn' => 'phpblock_tag_menu', 'block_type' => 'phpblock',
          'group_id' => 'admin_group_id', 'is_enabled' => 0),
);


/**
* Puts the datastructures for this plugin into the glFusion database
*
* Note: Corresponding uninstall routine is in functions.inc
*
* @return   boolean True if successful False otherwise
*
*/
function plugin_install_tag()
{
    global $INSTALL_plugin, $_TAG_CONF;

    $pi_name            = $_TAG_CONF['pi_name'];
    $pi_display_name    = $_TAG_CONF['pi_display_name'];
    $pi_version         = $_TAG_CONF['pi_version'];

    COM_errorLog("Attempting to install the $pi_display_name plugin", 1);

    $ret = INSTALLER_install($INSTALL_plugin[$pi_name]);
    if ($ret > 0) {
        return false;
    }

    return true;
}


/**
* Loads the configuration records for the Online Config Manager
*
* @return   boolean     true = proceed with install, false = an error occured
*
*/
function plugin_load_configuration_tag()
{
    global $_CONF;

    require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';

    return plugin_initconfig_tag();
}


function plugin_postinstall_tag()
{
	/**
	* Scan all tags that might already exist in stories in case of re-installation
	*/
	TAG_scanAllStories();
}


/**
* Automatic uninstall function for plugins
*
* @return   array
*
* This code is automatically uninstalling the plugin.
* It passes an array to the core code function that removes
* tables, groups, features and php blocks from the tables.
* Additionally, this code can perform special actions that cannot be
* foreseen by the core code (interactions with other plugins for example)
*
*/
function plugin_autouninstall_tag ()
{
    $out = array (
        /* give the name of the tables, without $_TABLES[] */
        'tables' => array('tag_list','tag_map','tag_badwords','tag_menu'),
        /* give the full name of the group, as in the db */
        'groups' => array('tag Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('tag.admin'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array('phpblock_tag_cloud','phpblock_tag_menu'),
        /* give all vars with their name */
        'vars'=> array()
    );
    return $out;
}
?>