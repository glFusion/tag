<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | install_defaults.php                                                     |
// |                                                                          |
// | Initialize the online configuration settings                             |
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

global $_DB_table_prefix, $_TABLES, $_TAG_DEFAULT;

// set Plugin Table Prefix the Same as glFusion

$_TAG_table_prefix = $_DB_table_prefix;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['tag_list']      = $_TAG_table_prefix . 'tag_list';
$_TABLES['tag_map']       = $_TAG_table_prefix . 'tag_map';
$_TABLES['tag_badwords']  = $_TAG_table_prefix . 'tag_badwords';
$_TABLES['tag_menu']      = $_TAG_table_prefix . 'tag_menu';

$_TAG_DEFAULT = array();

// Plugin info

$_TAG_DEFAULT['pi_version'] = '2.0.0';
$_TAG_DEFAULT['gl_version'] = '1.6.3';
$_TAG_DEFAULT['pi_url']     = 'https://www.glfusion.org/';

/**
* User Configurations
*/

/**
* Default name of a tag cloud block which will be created during the
* installation.
*/
$_TAG_DEFAULT['default_block_name'] = 'tag_cloud_block';

/**
* Tag name to be used in items (articles), like '[tag:foo]'.  You might prefer
* a shorter name like '[t:foo]'.
*/
$_TAG_DEFAULT['tag_name'] = 'tag';

/**
* Max length of a tag in bytes.  Should not be longer than 128.
*/
$_TAG_DEFAULT['max_tag_len'] = 60;

/**
* If this is true, the tag "glFusion" will NOT be identified with the tag
* "glfusion".
*/
$_TAG_DEFAULT['tag_case_sensitive'] = false;

/**
* If this is true, each tag consisting only of alphabets will be stemmed.  For
* example, tag "realize" will be stemmed into "real", thus tag "realize" will be
* identidied with tag "real".
*
* @WARNING: The stemming feature is still not perfect.  For example, 'Firefox'
*           is stemmed into 'Firefoxi'.  So, I don't recommend you set
*           $_TAG_DEFAULT['tag_stemming'] to true for the time being.
*/
$_TAG_DEFAULT['tag_stemming'] = false;

/**
* Whether to use a list of bad words.  If a tag is regarded as bad, it will be
* replaced with $LANG_TAG['badword_replace'] automatically.
*/
$_TAG_DEFAULT['tag_check_badword'] = true;

/**
* A string to be used as a spacer in displaying tag clouds
*/
$_TAG_DEFAULT['tag_cloud_spacer'] = '  ';

/**
* Max number of tags to be displayed in tag clouds in public_html/tag/index.php
*/
$_TAG_DEFAULT['max_tag_cloud'] = 30;

/**
* Max number of tags to be displayed in tag clouds in side block
*/
$_TAG_DEFAULT['max_tag_cloud_in_block'] = 20;

/**
* Thresholds of frequency of each tag cloud level
*
* All tag clouds are classified in 10 levels (level 0..level 9).  Those tags
* whose number is equal to or smaller than $_TAG_DEFAULT['tag_cloud_threshold'][x]
* belong to level x.  Each level corresponds to its own class in CSS(Cascading
* Style Sheet), so you can display in different styles tags according to their
* levels.
*/
$_TAG_DEFAULT['tag_cloud_threshold'][0]  = 1;
$_TAG_DEFAULT['tag_cloud_threshold'][1]  = 2;
$_TAG_DEFAULT['tag_cloud_threshold'][2]  = 3;
$_TAG_DEFAULT['tag_cloud_threshold'][3]  = 4;
$_TAG_DEFAULT['tag_cloud_threshold'][4]  = 5;
$_TAG_DEFAULT['tag_cloud_threshold'][5]  = 6;
$_TAG_DEFAULT['tag_cloud_threshold'][6]  = 7;
$_TAG_DEFAULT['tag_cloud_threshold'][7]  = 8;
$_TAG_DEFAULT['tag_cloud_threshold'][8]  = 9;

/**
* Whether to replace an underscore included in tag texts with a space
*/
$_TAG_DEFAULT['replace_underscore'] = true;

/**
* The number of key words to be included in <meta name="keywords"
* content="foo,bar"> tag
*/
$_TAG_DEFAULT['num_keywords'] = 0;

/**
* Whether to publish tags as template vars which can be used in
* 'storytext.thtml', 'featuredstorytext.thtml' and 'archivestorytext.thtml'.
* This idea was provided by dengen.
*
*/
$_TAG_DEFAULT['publish_as_template_vars'] = false;

/**
* This is work vars and should be left untouched by users
*/
$_TAG_DEFAULT['template_vars'] = array();

/**
* Enable what's related hook
*/
$_TAG_DEFAULT['enable_whatsrelated'] = false;

/**
* Maximum number of items to return in what's related
*/
$_TAG_DEFAULT['whatsrelated_limit'] = 10;

/**
* Which glFusion blocks to display
*/

$_TAG_DEFAULT['displayblocks'] = 0;

/**
* Initialize Tag plugin configuration
*
* Creates the database entries for the configuation if they don't already exist.
* Initial values will be taken from $_TAG_CONF if available (e.g. from an old
* config.php), uses $_TAG_CONF otherwise.
*
* @return   boolean     true: success; false: an error occurred
*/
function plugin_initconfig_tag() {
    global $_TAG_CONF, $_TAG_DEFAULT;

    if (isset($_TAG_CONF) AND is_array($_TAG_CONF)
    AND (count($_TAG_CONF) >= 1)) {
        $_TAG_DEFAULT = array_merge($_TAG_DEFAULT, $_TAG_CONF);
    }

    $c = config::get_instance();
    $c->add('sg_main', NULL, 'subgroup', 0, 0, NULL, 0, true, 'tag');
    $c->add('fs_main', NULL, 'fieldset', 0, 0, NULL, 0, true, 'tag');

    /**
    * Main
    */
    $c->add('default_block_name', $_TAG_DEFAULT['default_block_name'], 'text', 0, 0, NULL, 10, true, 'tag');
    $c->add('tag_name', $_TAG_DEFAULT['tag_name'], 'text', 0, 0, NULL, 20, true, 'tag');
    $c->add('max_tag_len', $_TAG_DEFAULT['max_tag_len'], 'text', 0, 0, null, 30, true, 'tag');
    $c->add('tag_case_sensitive', $_TAG_DEFAULT['tag_case_sensitive'], 'select', 0, 0, 0, 40, true, 'tag');
    $c->add('tag_stemming', $_TAG_DEFAULT['tag_stemming'], 'select', 0, 0, 0, 50, true, 'tag');
    $c->add('tag_check_badword', $_TAG_DEFAULT['tag_check_badword'], 'select', 0, 0, 0, 60, true, 'tag');
    $c->add('tag_cloud_spacer', $_TAG_DEFAULT['tag_cloud_spacer'], 'text', 0, 0, null, 70, true, 'tag');
    $c->add('max_tag_cloud', $_TAG_DEFAULT['max_tag_cloud'], 'text', 0, 0, null, 80, true, 'tag');
    $c->add('max_tag_cloud_in_block', $_TAG_DEFAULT['max_tag_cloud_in_block'], 'text', 0, 0, null, 90, true, 'tag');
    $c->add('tag_cloud_threshold', $_TAG_DEFAULT['tag_cloud_threshold'], '%text', 0, 0, null, 100, true, 'tag');
    $c->add('replace_underscore', $_TAG_DEFAULT['replace_underscore'], 'select', 0, 0, 0, 110, true, 'tag');
    $c->add('num_keywords', $_TAG_DEFAULT['num_keywords'], 'text', 0, 0, NULL, 110, true, 'tag');
    $c->add('publish_as_template_vars', $_TAG_DEFAULT['publish_as_template_vars'], 'select', 0, 0, 0, 120, true, 'tag');
    $c->add('enable_whatsrelated', $_TAG_DEFAULT['enable_whatsrelated'], 'select', 0, 0, 0, 125, true, 'tag');
    $c->add('whatsrelated_limit', $_TAG_DEFAULT['whatsrelated_limit'], 'text', 0, 0, null, 130, true, 'tag');
    $c->add('displayblocks', $_TAG_DEFAULT['displayblocks'], 'select', 0, 0, 1, 150, true, 'tag');

    return true;
}
?>