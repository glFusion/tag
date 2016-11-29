<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | scan_ajax.php                                                            |
// |                                                                          |
// | Ajax content scanner                                                     |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2016 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Tag Plugin                                                  |
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

require_once '../../../lib-common.php';

function TAG_ajaxGetPluginList()
{
    global $_CONF, $_TAG_CONF, $_TABLES, $_PLUGINS;

    $pluginList = array();
    $retval = array();

    if ( !COM_isAjax()) die();

    $pluginList[] = 'article';
    foreach ($_PLUGINS as $pi_name) {
        if ( is_callable('plugin_getiteminfo_' . $pi_name) && $pi_name != 'forum') {
            $pluginList[] = $pi_name;
        }
    }
    $retval['errorCode'] = 0;
    $retval['pluginlist'] = $pluginList;
    $return["js"] = json_encode($retval);
    echo json_encode($return);
    exit;
}


function TAG_ajaxScanPlugin( $pi_name )
{
    global $_CONF, $_TAG_CONF, $_TABLES, $_PLUGINS;

    if ( !COM_isAjax()) die();

    if ( $pi_name == 'article' ) {
        TAG_scanAllStories();
    } else {
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
    $retval['errorCode'] = 0;
    $return["js"] = json_encode($retval);
    echo json_encode($return);
    exit;
}

$mode = '';

if ( !isset($_POST['mode'])) {
    die();
}
$mode = COM_applyFilter($_POST['mode']);

if ( isset($_POST['cancelbutton'])) $mode = '';

$retval = array();

switch ( $mode ) {
    case 'pluginlist' :
        TAG_ajaxGetPluginList();
        break;
    case 'scanplugin' :
        if ( !isset($_POST['plugin'])) die();
        $pi_name = COM_applyFilter($_POST['plugin']);
        TAG_ajaxScanPlugin( $pi_name );
        break;
    case 'complete' :
        CTL_clearCache();
        $retval['errorCode'] = 0;
        $return["js"] = json_encode($retval);
        echo json_encode($return);
        exit;
    default :
        COM_refresh($_CONF['site_admin_url'].'/plugins/tag/index.php');
        break;
}

?>