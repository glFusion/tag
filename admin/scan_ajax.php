<?php
/**
* Tag Plugin for glFusion CMS
*
* Ajax content scanner
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