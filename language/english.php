<?php
/**
* Tag Plugin for glFusion CMS
*
* English Language File
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

$LANG_TAG = array(
    'plugin'            => 'Tag Plugin',
    'access_denied'     => 'Access Denied',
    'access_denied_msg' => 'Only Root Users have Access to this Page.  Your user name and IP have been recorded.',
    'admin'		        => 'tag Plugin Admin',
    'install_header'	=> 'Install/Uninstall the tag Plugin',
    'install_success'	=> 'Installation Successful',
    'install_fail'	    => 'Installation Failed -- See your error log to find out why.',
    'uninstall_success'	=> 'Uninstallation Successful',
    'uninstall_fail'    => 'Installation Failed -- See your error log to find out why.',
    'uninstall_msg'		=> 'Tag plugin was successfully uninstalled.',
    'version_required'  => 'Tag requires glFusion v1.1.0 or later.',
    'tag_separators'    => ' ',	// Can be more than one character
    'badword_replace'   => '',
    'admin_label'       => 'Tag',
    'display_label'     => 'Tag: ',
    'default_block_title' => 'Popular tags at this site',
    'tag_list'          => 'Tag list',
    'selected_tag'      => 'Items having <strong>%s</strong> tag: ',	// %s = tag name
    'related'           => 'Related tags',
    'block_title'       => 'Popular tags at this site',
    'menu_stats'        => 'Tag List',
    'menu_badword'      => 'Ignored Tags',
    'db_error'          => 'Cannot read from database.',
    'action'            => 'Action',
    'desc_admin_stats'  => 'This is the list of registered tags.  You can choose to ignore certain tags if needed. For example, if they are too popular or contain inappropriate words.',
    'lbl_tag'           => 'Tag',
    'lbl_count'         => 'Frequency',
    'lbl_hit_count'     => 'Number of clicks',
    'delete_checked'    => 'Allow checked tags',
    'ban_checked'       => 'Ignore checked tags',
    'desc_admin_badword' => 'This is the list of tags you would like to ignore, for example, too popular or too vulgar tags.',
    'check'             => 'Check',
    'add'               => 'Add',
    'edit'              => 'Edit',
    'delete'            => 'Delete',
    'submit'            => 'Submit',
    'cancel'            => 'Cancel',
    'badword'           => 'Ignored tags',
    'no_tag'            => 'No tags are defined yet.',
    'no_badword'        => 'No tags are ignored yet.',
    'add_success'       => 'Successfully added.',
    'add_fail'          => 'Cannot add.',
    'delete_success'    => 'Successfully deleted.',
    'delete_fail'       => 'Cannot delete.',
    'edit_success'      => 'Successfully modified.',
    'edit_fail'         => 'Cannot modify.',
    'menu_title'        => 'Items containing tags: %s',
    'no_item'           => 'No matching items found.',
    'no_title'          => 'No title available',
    'desc_tag'          => 'Link to list of all content items flagged with this specific tag',
    'admin_help'        => 'The Tag plugin enables you to put "tags" in your content (stories, pages, Media Gallery descriptions, etc.) to allow easier grouping and retrieval of similar content.',
    'ignore_confirm'    => 'Are you sure you want to ignore this tag?',
    'unban_confirm'     => 'Are you sure you want to allow this tag?',
    'menu_rescan'       => 'Rescan Content',
    'cancel'            => 'Cancel',
    'rescan'            => 'Rescan',
    'rescan_instructions' => 'This will rescan all glFusion content and rebuild the tag mappings. This process will rescan all content, so it could take some time to run. You generally do not need to rescan all content. If you have re-installed the Tag Plugin, rescanning should be done, othrwise, it should not be necessary.',
    'rescan_title'      => 'Rescan All Content',
    'rescan_complete'   => 'Rescanning of content has completed',
);

// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Tag plugin',
    'title' => 'Tag plugin Config'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
    'default_block_name'       => 'Default name for Tag Cloud Block',
    'tag_name'                 => 'Tag name',
    'max_tag_len'              => 'Max length of tag in bytes',
    'tag_case_sensitive'       => 'Tag case-sensitive',
    'tag_stemming'             => 'Allow stemming words',
    'tag_check_badword'        => 'Use list of bad words',
    'tag_cloud_spacer'         => 'String to be used as spacer in Tag Cloud',
    'max_tag_cloud'            => 'Max number of tags in Tag Cloud',
    'max_tag_cloud_in_block'   => 'Max Number of tgas in Tag Cloud Block',
    'tag_cloud_threshold'      => 'Threshold of Tag Levels',
    'replace_underscore'       => 'Replace an underscore with a space',
    'num_keywords'             => 'Max number of keywords',
    'publish_as_template_vars' => 'Publish tags as template vars',
    'displayblocks'            => 'Display glFusion Blocks',
    'enable_whatsrelated'      => 'Replace Story What\'s Related Block',
    'whatsrelated_limit'       => 'Maximum number of items to return in What\'s Related block',
    'limit_related_types'      => 'Allowed content types for What\'s Related (comma delimited list)',
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Main'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'Tag plugin Main Config',
);

$LANG_configselects['tag'] = array(
    0 => array('Yes' => true, 'No' => false),
    1 => array('Left Blocks' => 0, 'Right Blocks' => 1, 'Left & Right Blocks' => 2, 'None' => 3)
);
?>