<?php
/**
* Tag Plugin for glFusion CMS
*
* Default Configuration
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

$tagConfigData = array(
    array(
        'name' => 'sg_main',
        'default_value' => 'N;',
        'type' => 'subgroup',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 0,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'fs_main',
        'default_value' => 'N;',
        'type' => 'fieldset',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 0,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'default_block_name',
        'default_value' => 'tag_cloud_block',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 10,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'tag_name',
        'default_value' => 'tag',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 20,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'max_tag_len',
        'default_value' => '60',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 30,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'tag_case_sensitive',
        'default_value' => '',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 40,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'tag_check_badword',
        'default_value' => '1',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 50,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'tag_cloud_spacer',
        'default_value' => '  ',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 60,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'max_tag_cloud',
        'default_value' => '30',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 70,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'max_tag_cloud_in_block',
        'default_value' => '20',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 80,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'tag_cloud_threshold',
        'default_value' => array(1,2,3,4,5,6,7,8,9),
        'type' => '%text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 90,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'replace_underscore',
        'default_value' => '1',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 100,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'num_keywords',
        'default_value' => '0',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 110,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'publish_as_template_vars',
        'default_value' => '',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 120,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'enable_whatsrelated',
        'default_value' => '',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 130,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'whatsrelated_limit',
        'default_value' => '10',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 140,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'limit_related_types',
        'default_value' => '',
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => -1,
        'sort' => 150,
        'set' => TRUE,
        'group' => 'tag'
    ),
    array(
        'name' => 'displayblocks',
        'default_value' => '0',
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 1,
        'sort' => 160,
        'set' => TRUE,
        'group' => 'tag'
    )
);
?>
