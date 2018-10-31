<?php
/**
* Tag Plugin for glFusion CMS
*
* Installation Routines
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
require_once $_CONF['path'].'plugins/tag/autoinstall.php';

USES_lib_install();

/**
* Only let Root users access this page
*/
if (!SEC_inGroup('Root')) {
    COM_accessLog("User {$_USER['username']} tried to access the Tag installation.");
    COM_404();
    exit;
}

/**
* Main Function
*/
if (SEC_checkToken()) {
    $action = COM_applyFilter($_GET['action']);
    if ($action == 'install') {
        if (plugin_install_tag()) {
            // Redirects to the plugin editor
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins.php?msg=44');
            exit;
        } else {
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins.php?msg=72');
            exit;
        }
    } else if ($action == 'uninstall') {
        if (plugin_uninstall_tag('installed')) {
            /**
            * Redirects to the plugin editor
            */
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins.php?msg=45');
            exit;
        } else {
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins.php?msg=73');
            exit;
        }
    }
}

echo COM_refresh($_CONF['site_admin_url'] . '/plugins.php');
?>