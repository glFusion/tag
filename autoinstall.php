<?php
/**
* Tag Plugin for glFusion CMS
*
* Auto installer
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

global $_DB_dbms;

require_once $_CONF['path'].'plugins/tag/functions.inc';
require_once $_CONF['path'].'plugins/tag/tag.php';
require_once $_CONF['path'].'plugins/tag/sql/mysql_install.php';

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

  array('type' => 'group', 'group' => 'tag Admin', 'desc' => 'Users in this group can administer the tag plugin',
        'variable' => 'admin_group_id', 'addroot' => true, 'admin' => true),

  array('type' => 'feature', 'feature' => 'tag.admin', 'desc' => 'Ability to administer the tag plugin',
        'variable' => 'admin_feature_id'),

  array('type' => 'mapping', 'group' => 'admin_group_id', 'feature' => 'admin_feature_id',
        'log' => 'Adding Tag feature to the Tag admin group'),

  array('type' => 'sql', 'sql' => $_SQL['tag_list_index']),

  array('type' => 'sql', 'sql' => $_SQL['tag_map_index']),

  array('type' => 'block', 'name' => 'block_tag', 'title' => 'Popular tags at this site',
          'phpblockfn' => 'phpblock_tag_cloud', 'block_type' => 'phpblock',
          'group_id' => 'admin_group_id' , 'onleft' => true),
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
    require_once dirname(__FILE__) . '/install_defaults.php';
    return plugin_initconfig_tag();
}


function plugin_postinstall_tagXX()
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
        'tables' => array('tag_list','tag_map'),
        /* give the full name of the group, as in the db */
        'groups' => array('tag Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('tag.admin'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array('phpblock_tag_cloud'),
        /* give all vars with their name */
        'vars'=> array()
    );
    return $out;
}
?>