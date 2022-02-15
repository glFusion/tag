<?php
/**
* Tag Plugin for glFusion CMS
*
* Upgrade Routines
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2010-2022 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*
*  Based on the Original Work from Tag Plugin
*  @copyright  Copyright (c) 2008 mystral-kk - geeklog AT mystral-kk DOT net
*
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

// this function is called by lib-plugin whenever the 'Upgrade' option is
// selected in the Plugin Administration screen for this plugin

function tag_upgrade()
{
    global $_TABLES, $_CONF, $_TAG_CONF, $_DB_table_prefix;

    $currentVersion = DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='tag'");

    switch ($currentVersion) {
        case '0.1.0':	// 0.1.0 --> 0.2.0
            $sql = "CREATE INDEX idx_tag_list_tag ON {$_TABLES['tag_list']} (tag)";
            DB_query($sql);
            $sql = "CREATE INDEX idx_tag_map_tag_id ON {$_TABLES['tag_map']} (tag_id)";
            DB_query($sql);

        case '0.2.0':	// 0.2.0 --> 0.3.0
        case '0.3.0':	// 0.3.0 --> 0.3.1
        case '0.3.1':
        case '0.3.2':
            require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';
            plugin_initconfig_tag();

        case '0.4.1':
        case '0.4.2':
        case '0.4.3':
        case '0.5.0':
        case '0.5.1':
            DB_query("UPDATE {$_TABLES['tag_map']} SET type='staticpages' WHERE type='staticpage'",1);

        case '0.5.2' :
        case '0.5.3' :
        case '0.5.4' :
            DB_query("UPDATE {$_TABLES['groups']} SET grp_gl_core=2 WHERE grp_name='tag Admin'",1);

        case '1.0.1' :
        case '1.0.2' :
            DB_query("ALTER TABLE {$_TABLES['tag_badwords']} DROP PRIMARY KEY, ADD PRIMARY KEY (`badword`(191));",1);
            DB_query("ALTER TABLE {$_TABLES['tag_list']} CHANGE `tag` `tag` VARCHAR(128) NOT NULL DEFAULT '';",1);
            DB_query("ALTER TABLE {$_TABLES['tag_list']} DROP INDEX `idx_tag_list_tag`, ADD UNIQUE `idx_tag_list_tag` (`tag`);",1);
            DB_query("ALTER TABLE {$_TABLES['tag_list']} ADD `ignore_tag` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `tag`;",1);
            DB_query("ALTER TABLE {$_TABLES['tag_map']} CHANGE `sid` `sid` VARCHAR(128) NOT NULL DEFAULT '';",1);
            // remove the menu block
            DB_query("DELETE FROM {$_TABLES['blocks']} WHERE name='block_tag_menu'",1);
            // transfer badwords to main tag_list table
            $badwordList = array();
            $result = DB_query("SELECT * FROM {$_TABLES['tag_badwords']}");
            while ( ($row = DB_fetchArray($result) != false )) {
                $badwordList[] = $row['badword'];
            }
            foreach ($badwordList AS $badword ) {
                $sql = "REPLACE INTO {$_TABLES['tag_list']} (tag,ignore_tag) VALUES ('" . DB_escapeString($badword) . "',1)";
                DB_query($sql);
            }
            DB_query("DROP TABLE {$_TABLES['tag_badwords']};",1);
            DB_query("DROP TABLE {$_TABLES['tag_menu']};",1);

        case '2.0.0' :

        case '2.0.1' :
            // no changes

        case '2.0.2' :
            // no changes

        case '2.1.0' :
            // no changes

        case '2.1.1' :
            // no changes

        case '2.1.2' :
            // no changes

        default:
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='".$_TAG_CONF['pi_version']."',pi_gl_version='".$_TAG_CONF['gl_version']."' WHERE pi_name='tag' LIMIT 1");
            break;
    }

    tag_update_config();

    CTL_clearCache();

    if ( DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='tag'") == $_TAG_CONF['pi_version']) {
        return true;
    } else {
        return false;
    }
}

function tag_update_config()
{
    global $_CONF, $_TAG_CONF, $_TABLES;

//    $c = config::get_instance();

    require_once $_CONF['path'].'plugins/tag/sql/tag_config_data.php';

    // remove stray items
    $result = DB_query("SELECT * FROM {$_TABLES['conf_values']} WHERE group_name='tag'");
    while ( $row = DB_fetchArray($result) ) {
        $item = $row['name'];
        if ( ($key = _searchForIdKey($item,$tagConfigData)) === NULL ) {
            DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE name='".DB_escapeString($item)."' AND group_name='tag'");
        } else {
            $tagConfigData[$key]['indb'] = 1;
        }
    }
    // add any missing items
    foreach ($tagConfigData AS $cfgItem ) {
        if (!isset($cfgItem['indb']) ) {
            _addConfigItem( $cfgItem );
        }
    }
    $c = config::get_instance();
    $c->initConfig();
    $tcnf = $c->get_config('tag');
    // sync up sequence, etc.
    foreach ( $tagConfigData AS $cfgItem ) {
        $c->sync(
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

if ( !function_exists('_searchForId')) {
    function _searchForId($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['name'] === $id) {
               return $array[$key];
           }
       }
       return null;
    }
}

if ( !function_exists('_searchForIdKey')) {
    function _searchForIdKey($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['name'] === $id) {
               return $key;
           }
       }
       return null;
    }
}

if ( !function_exists('_addConfigItem')) {
    function _addConfigItem($data = array() )
    {
        global $_TABLES;

        $Qargs = array(
                       $data['name'],
                       $data['set'] ? serialize($data['default_value']) : 'unset',
                       $data['type'],
                       $data['subgroup'],
                       $data['group'],
                       $data['fieldset'],
                       ($data['selection_array'] === null) ?
                        -1 : $data['selection_array'],
                       $data['sort'],
                       $data['set'],
                       serialize($data['default_value']));
        $Qargs = array_map('DB_escapeString', $Qargs);

        $sql = "INSERT INTO {$_TABLES['conf_values']} (name, value, type, " .
            "subgroup, group_name, selectionArray, sort_order,".
            " fieldset, default_value) VALUES ("
            ."'{$Qargs[0]}',"   // name
            ."'{$Qargs[1]}',"   // value
            ."'{$Qargs[2]}',"   // type
            ."{$Qargs[3]},"     // subgroup
            ."'{$Qargs[4]}',"   // groupname
            ."{$Qargs[6]},"     // selection array
            ."{$Qargs[7]},"     // sort order
            ."{$Qargs[5]},"     // fieldset
            ."'{$Qargs[9]}')";  // default value

        DB_query($sql);
    }
}
?>
