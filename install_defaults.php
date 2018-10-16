<?php
/**
* Tag Plugin for glFusion CMS
*
* Installation
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

/** Utility plugin configuration data
*   @global array */
global $_TAG_CONF;
if (!isset($_TAG_CONF) || empty($_TAG_CONF)) {
    $_TAG_CONF = array();
    require_once dirname(__FILE__) . '/tag.php';
}

/**
*   Initialize Tag plugin configuration
*
*   @return boolean             true: success; false: an error occurred
*/
function plugin_initconfig_tag()
{
    global $_CONF;

    $c = config::get_instance();

    if (!$c->group_exists('tag')) {
        require_once $_CONF['path'].'plugins/tag/sql/tag_config_data.php';

        foreach ( $tagConfigData AS $cfgItem ) {
            $c->add(
                $cfgItem['name'],
                $cfgItem['default_value'],
                $cfgItem['type'],
                $cfgItem['subgroup'],
                $cfgItem['fieldset'],
                $cfgItem['selection_array'],
                $cfgItem['sort'],
                $cfgItem['set'],
                $cfgItem['group']
            );
        }
     }
     return true;
}
?>
