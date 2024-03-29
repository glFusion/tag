<?php
/**
* Tag Plugin for glFusion CMS
*
* Admin Interface
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
require_once '../../auth.inc.php';

USES_lib_admin();

/**
* Only let admin users access this page
*/
if (!SEC_hasRights('tag.admin')) {
    COM_accessLog("User {$_USER['username']} tried to access the Tag admin screen.");
    COM_404();
    exit;
}

/*
 * Displays the main tag list
 */
function viewTagList()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_TAG, $_IMAGE_TYPE;

    $retval = "";

    $header_arr = array(
            array('text' => $LANG_TAG['lbl_tag'], 'field' => 'tag', 'sort' => true, 'align' => 'left'),
            array('text' => $LANG_TAG['lbl_count'], 'field' => 'cnt', 'sort' => true, 'align' => 'center'),
            array('text' => $LANG_TAG['lbl_hit_count'], 'field' => 'hits', 'sort' => true, 'align' => 'center'),
    );

    $defsort_arr = array('field'     => 'cnt',
                         'direction' => 'DESC');

    $text_arr = array(
            'form_url'      => $_CONF['site_admin_url'] . '/plugins/tag/index.php',
            'help_url'      => '',
            'has_search'    => true,
            'has_limit'     => true,
            'has_paging'    => true,
            'no_data'       => $LANG_TAG['no_tag'],
    );

    $sql = "SELECT L.tag_id, L.tag, COUNT(m.tag_id) AS cnt, L.hits "
            . "FROM {$_TABLES['tag_list']} AS L "
            . "JOIN {$_TABLES['tag_map']} AS m "
            . "ON L.tag_id = m.tag_id ";

    $query_arr = array('table' => 'tag_list',
                        'sql' => $sql,
                        'query_fields' => array('tag'),
                        'default_filter' => " WHERE ignore_tag != 1 ",
                        'group_by' => "m.tag_id");

    $filter = "";

    $actions = '<input name="delsel" type="image" src="'
            . $_CONF['layout_url'] . '/images/admin/delete.' . $_IMAGE_TYPE
            . '" style="vertical-align:bottom;" title="' . $LANG_TAG['ban_checked']
            . '" onclick="return confirm(\'' . $LANG_TAG['ignore_confirm'] . '\');"'
            . ' value="' . $LANG_TAG['ban_checked'] . '" '
            . '/>&nbsp;' . $LANG_TAG['ban_checked'];

    $option_arr = array('chkselect' => true,
            'chkfield' => 'tag_id',
            'chkname' => 'tag_ids',
            'chkminimum' => 0,
            'chkall' => false,
            'chkactions' => $actions
    );

    $formfields = '
            <input name="cmd" type="hidden" value="stats">
            <input name="action" type="hidden" value="bansel">
    ';

    $form_arr = array('top' => $formfields);

    $retval .= ADMIN_list('taglist', 'TAG_getListField', $header_arr,
    $text_arr, $query_arr, $defsort_arr, $filter, "", $option_arr, $form_arr);

    return $retval;
}


function viewBanList()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_TAG, $_IMAGE_TYPE;

    $retval = "";

    $header_arr = array(      # display 'text' and use table field 'field'
            array('text' => $LANG_TAG['lbl_tag'], 'field' => 'tag', 'sort' => true, 'align' => 'left'),
    );

    $defsort_arr = array('field'     => 'tag',
                         'direction' => 'ASC');

    $text_arr = array(
            'form_url'      => $_CONF['site_admin_url'] . '/plugins/tag/index.php?viewban=x',
            'help_url'      => '',
            'has_search'    => true,
            'has_limit'     => true,
            'has_paging'    => true,
            'no_data'       => $LANG_TAG['no_badword'],
    );

    $sql = "SELECT * FROM {$_TABLES['tag_list']}";

    $query_arr = array('table' => 'tag_list',
            'sql' => $sql,
            'query_fields' => array('tag'),
            'default_filter' => " WHERE ignore_tag=1 ");

    $filter = "";

    $actions = '<input name="delban" type="image" src="'
            . $_CONF['layout_url'] . '/images/admin/delete.' . $_IMAGE_TYPE
            . '" style="vertical-align:bottom;" title="' . "delete them"
            . '" onclick="return confirm(\'' . $LANG_TAG['unban_confirm'] . '\');"'
            . ' value="' . $LANG_TAG['delete_checked'] . '" '
            . '/>&nbsp;' . $LANG_TAG['delete_checked'];

    $option_arr = array('chkselect' => true,
            'chkfield' => 'tag',
            'chkname' => 'words',
            'chkminimum' => 0,
            'chkall' => true,
            'chkactions' => $actions
    );

    $formfields = '
            <input name="action" type="hidden" value="bandel">
            <input name="word" type="text">
            <input name="addban" type="submit" value="'.$LANG_TAG['add'].'">
            ';

    $form_arr = array('top' => $formfields);

    $retval .= ADMIN_list('tagbanlist', 'TAG_getListField', $header_arr,
    $text_arr, $query_arr, $defsort_arr, $filter, "", $option_arr, $form_arr);

    return $retval;
}

function TAG_getListField($fieldname, $fieldvalue, $A, $icon_arr, $token = "")
{
    global $_CONF;

    $retval = '';

    switch ($fieldname) {

        case 'tag' :
            $url = $_CONF['site_admin_url'].'/plugins/tag/index.php?displaytag=x&id='.urlencode($A['tag_id']);
            $retval = '<a href="'.$url.'">'.htmlspecialchars($fieldvalue, ENT_QUOTES, COM_getEncodingt()).'</a> ';
            break;

        case 'cnt' :
        case 'hits' :
            $retval = (int) $fieldvalue;
            break;

        default:
            $retval = $fieldvalue;
            break;
    }

    return $retval;
}


/*
* need to rework the submit / processing code a lot
*/

function badwordDelete()
{
    global $_TABLES, $LANG_TAG;

    $words = $_POST['words'];
    if (!is_array($words) || count($words) == 0) {
        return;
    }

    /**
    * Delete a bad word from DB
    */
    $words4db = array_map('DB_escapeString', $words);
    $words4db = "('" . implode("','", $words4db) . "')";

    $sql = "UPDATE {$_TABLES['tag_list']} SET ignore_tag=0 WHERE (tag IN " . $words4db . ")";

    $result = DB_query($sql);

    // delete all tags that do not have a corresponding tag_map entry and ignore_tag = 0

    $sql = "DELETE FROM {$_TABLES['tag_list']} WHERE ignore_tag = 0 AND tag_id=ANY(
            SELECT sub.tag_id FROM (
            SELECT L.tag_id, L.tag, L.ignore_tag, COUNT(m.tag_id) AS cnt, L.hits
            FROM {$_TABLES['tag_list']} AS L
            LEFT JOIN {$_TABLES['tag_map']} AS m ON L.tag_id = m.tag_id WHERE ignore_tag=0 GROUP BY L.tag_id
            ) sub
            WHERE sub.cnt = 0);";
    $result = DB_query($sql);

    CTL_clearCache();

    return DB_error() ? $LANG_TAG['delete_fail'] : $LANG_TAG['delete_success'];
}

function addBadword()
{
    global $_TABLES, $LANG_TAG;

    if (!isset($_POST['word'])) {
        return;
    }
    $word = COM_applyFilter($_POST['word']);

    $sql = "REPLACE INTO {$_TABLES['tag_list']} (tag,ignore_tag) "
    . "VALUES ('" . DB_escapeString($word) . "',1)";
    $result = DB_query($sql);

    CACHE_remove_instance('tagcloud');
    CACHE_remove_instance('tagkwd');

    return DB_error() ? $LANG_TAG['add_fail'] : $LANG_TAG['add_success'];
}

function deleteTag()
{
    global $_TABLES, $LANG_TAG;

    if (!isset($_POST['tag_ids'])) {
        return;
    }

    $tag_ids = $_POST['tag_ids'];
    if (!is_array($tag_ids) || count($tag_ids) == 0) {
        return;
    }

    $tag_ids = array_map('DB_escapeString', $tag_ids);
    $tag_ids = "'" . implode("','", $tag_ids) . "'";

    // update DB and mark as ignored

    $sql = "UPDATE {$_TABLES['tag_list']} SET ignore_tag=1 WHERE (tag_id IN ({$tag_ids}))";
    $result = DB_query($sql);

    CACHE_remove_instance('tagcloud');
    CACHE_remove_instance('tagkwd');

    return DB_error() ? $LANG_TAG['delete_fail'] : $LANG_TAG['delete_success'];
}


function displayTag( $tag )
{
    global $_CONF, $_TAG_CONF, $LANG_TAG;

    /**
    * Display
    */
    $T = new Template($_CONF['path'] . 'plugins/tag/templates');
    $T->set_file('page', 'index.thtml');

    /**
    * Lang vars
    */
    $lang_vars = array('tag_list');

    foreach ($lang_vars as $lang_var) {
        $T->set_var('lang_' . $lang_var, $LANG_TAG[$lang_var]);
    }

    /**
    * Other tags
    */
    if ($tag != '') {
        $tag = TAG_normalize($tag);
        $tag_id = TAG_getTagId($tag);
        if ($tag_id !== false) {
            $text = $tag;
            if ($_TAG_CONF['replace_underscore'] === true) {
                $text = str_replace('_', ' ', $text);
            }
            $T->set_var('selected_tag', sprintf($LANG_TAG['selected_tag'], htmlspecialchars($text, ENT_QUOTES, COM_getEncodingt())));
        }

        $T->set_var('tagged_items', ($tag != '') ? TAG_getTaggedItems($tag) : '');
    }

    $T->parse('output', 'page');

    return $T->finish($T->get_var('output'));
}

function rescanConfirm()
{
    global $_CONF, $_TAG_CONF, $LANG_TAG;

    $retval = '';

    $T = new Template($_CONF['path'] . 'plugins/tag/templates');
    $T->set_file('page','rescan_confirm.thtml');

    $T->set_var(array(
        'lang_cancel' => $LANG_TAG['cancel'],
        'lang_rescan' => $LANG_TAG['rescan'],
        'lang_rescan_instructions' => $LANG_TAG['rescan_instructions'],
        'lang_title' => $LANG_TAG['rescan_title'],

        'lang_scanning' => 'Scanning',
        'lang_scan'     => $LANG_TAG['rescan'],
        'lang_success'  => 'Scanning content complete.',
        'lang_ok'       => 'OK',
        'lang_ajax_status' => 'Status',

    ));

    $T->parse('output','page');
    $retval .= $T->finish($T->get_var('output'));
    return $retval;

}

function _tag_admin_menu($cmd = 'viewtags')
{
    global $_CONF, $LANG_ADMIN, $LANG_TAG;

    $retval = '';
    $menu_arr = array();

    $menu_arr[] = array('url' => $_CONF['site_admin_url'].'/plugins/tag/index.php?viewtags=x',
                'text' => $LANG_TAG['menu_stats'], 'active' => ($cmd=='viewtags' ? true : false));
    $menu_arr[] = array('url' => $_CONF['site_admin_url'].'/plugins/tag/index.php?viewban=x',
                'text' => $LANG_TAG['menu_badword'],'active' => ($cmd=='viewban' ? true : false));
    $menu_arr[] = array('url' => $_CONF['site_admin_url'].'/plugins/tag/index.php?rescan=x',
                'text' => $LANG_TAG['menu_rescan'],'active' => ($cmd=='rescan' ? true : false));

    if ($cmd == 'displaytag') {
        $menu_arr[] = array('url' => '',
                    'text' => 'Tag Info','active' => true);
    }

    $menu_arr[] = array('url' => $_CONF['site_admin_url'].'/index.php',
                'text' => $LANG_ADMIN['admin_home']);

    $retval .= ADMIN_createMenu(
        $menu_arr,
        $LANG_TAG['admin_help'],
        $_CONF['site_url'] . '/tag/images/tag.png'
    );
    return $retval;

}

// main controller

$display = '';
$page = '';
$cmd = 'viewtags';
$msg = '';

$expectedActions = array('viewtags','viewban','displaytag','rescan','dorescan','addban','delsel_x','bansel','delban_x');
foreach ( $expectedActions AS $action ) {
    if ( isset($_POST[$action])) {
        $cmd = $action;
    } elseif ( isset($_GET[$action])) {
        $cmd = $action;
    }
}
if ( isset($_POST['cancel'])) {
    $src = COM_applyFilter($_POST['cancel']);
    $cmd = 'viewtags';
}

switch ($cmd) {
    case 'addban' :
        $rc = addBadword();
        if ( $rc != '' ) {
            COM_setMsg( $rc, 'success' );
        }
        $cmd = 'viewban';
        $title = $LANG_TAG['menu_badword'];
        $page .= viewBanList();
        break;
    case 'delsel_x' : // delete a tag
        $rc = deleteTag();
        if ( $rc != '' ) {
            COM_setMsg( $rc, 'success' );
        }
        $cmd = 'viewtags';
        $title = $LANG_TAG['menu_stats'];
        $page   .= viewTagList();
        break;
    case 'delban_x' :
        $rc = badwordDelete();
        if ($rc != '') {
            COM_setMsg($rc,'success');
        }
        $cmd = 'viewban';
        $title = $LANG_TAG['menu_badword'];
        $page = viewBanList();
        break;
    case 'viewban' :
        $title = $LANG_TAG['menu_badword'];
        $page .= viewBanList();
        break;
    case 'rescan' :
        $title = $LANG_TAG['menu_rescan'];
        $page .= rescanConfirm();
        break;
    case 'dorescan' :
        TAG_scanAll();
        COM_setMsg( $LANG_TAG['rescan_complete'], 'success' );
        $page .= viewTagList();
        $title = $LANG_TAG['menu_stats'];
        break;
    case 'displaytag' :
        if ( isset($_GET['id'])) {
            $tag_id = COM_applyFilter($_GET['id'],true);
            $tag_name = TAG_getTagName($tag_id);
            $page .= displayTag($tag_name);
            $title = 'Tag Usage';
        } else {
            $title = $LANG_TAG['menu_stats'];
            $page .= viewTagList();
        }
        break;
    default :
        $cmd = 'viewtags';
        $title = $LANG_TAG['menu_stats'];
        $page .= viewTagList();
        break;
}

$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('admin', 'admin.thtml');

if ($msg != '') {
    $T->set_var('msg', '<p>' . $msg . '</p>');
}

$display  = COM_siteHeader();
$display .= COM_startBlock (ucfirst($LANG_TAG['admin']) . ' :: ' . $title, '',COM_getBlockTemplate ('_admin_block', 'header'));
$display .=_tag_admin_menu($cmd);
$display .= $page;
$display .= COM_endBlock (COM_getBlockTemplate ('_admin_block', 'footer'));
$display .= COM_siteFooter();

echo $display;
?>
