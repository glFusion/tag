<?php
/**
* Tag Plugin for glFusion CMS
*
* German Language File
*
* Translation by Tony Kluever
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
    die ('This file cannot be used on its own.');
}

###############################################################################

$LANG_TAG = array(
    'plugin' => 'Tag-Plugin',
    'access_denied' => 'Zugriff verweigert',
    'access_denied_msg' => 'Nur Root-Benutzer haben Zugriff auf diese Seite. Dein Benutzername und IP wurden aufgezeichnet.',
    'admin' => 'Tag-Plugin Administration',
    'install_header' => 'Tag-Plugin Installation/Deinstallation',
    'install_success' => 'Installation erfolgreich',
    'install_fail' => 'Installation fehlgeschlagen -- Schau in die Datei error.log f�r mehr Infos.',
    'uninstall_success' => 'Deinstallation erfolgreich',
    'uninstall_fail' => 'Deinstallation fehlgeschlagen -- Schau in die Datei error.log f�r mehr Infos.',
    'uninstall_msg' => 'Tag-Plugin wurde erfolgreich installiert.',
    'version_required' => 'Tag ben�tigt glFusion v1.1.0 oder h�her.',
    'tag_separators' => ' ',
    'badword_replace' => '',
    'admin_label' => 'Tag',
    'display_label' => 'Tag: ',
    'default_block_title' => 'Beliebte Tags auf dieser Seite',
    'tag_list' => 'Tag-Liste',
    'selected_tag' => 'Objekte, die <strong>%s</strong> Tag haben: ',
    'related' => '�hnliche Tags',
    'block_title' => 'Bleiebte Tags auf dieser Seite',
    'menu_stats' => 'Statistiken',
    'menu_badword' => 'Gesperrte Tags',
    'db_error' => 'Kann nicht von Datenbank lesen.',
    'action' => 'Aktion',
    'desc_admin_stats' => 'Dies ist die Liste der registrierten Tags. Du kannst falsch registrierte Tags l�schen oder Tags bannen (z.B. zu beliebte oder vulg�re Tags).',
    'lbl_tag' => 'Tag',
    'lbl_count' => 'Frequenz',
    'lbl_hit_count' => 'Anzahl der Klicks',
    'delete_checked' => 'Gepr�ften Tag l�schen',
    'ban_checked' => 'Gepr�fte Tags sperren',
    'desc_admin_badword' => 'Dies ist die Liste der Tags, die Du sperren willst, zum Beispiel, zu beliebte oder vulg�re Tags.',
    'check' => 'Pr�fen',
    'add' => 'Hinzuf�gen',
    'edit' => 'Bearbeiten',
    'delete' => 'L�schen',
    'submit' => 'Senden',
    'cancel' => 'Cancel',
    'badword' => 'Gesperrte Tags',
    'no_tag' => 'Noch keine Tags definiert.',
    'no_badword' => 'Noch keine Tags gesperrt.',
    'add_success' => 'Erfolgreich hinzugef�gt.',
    'add_fail' => 'Kann nicht hinuf�gen.',
    'delete_success' => 'Erfolgreich gel�scht.',
    'delete_fail' => 'Kann nicht l�schen.',
    'edit_success' => 'Erfolgreich ge�ndert.',
    'edit_fail' => 'Kann nicht �ndern.',
    'menu_title' => 'Objekte die Tags enthalten: %s',
    'no_item' => 'Keine passenden Objekte gefunden.',
    'no_title' => 'Kein Titel verf�gbar',
    'desc_tag' => 'Link to list of all content items flagged with this specific tag',
    'admin_help' => 'The Tag plugin enables you to put "tags" in your content (stories, pages, Media Gallery descriptions, etc.) to allow easier grouping and retrieval of similar content.',
    'ignore_confirm' => 'Are you sure you want to ignore this tag?',
    'unban_confirm' => 'Are you sure you want to allow this tag?',
    'menu_rescan' => 'Rescan Content',
    'cancel'            => 'Cancel',
    'rescan' => 'Rescan',
    'rescan_instructions' => 'This will rescan all glFusion content and rebuild the tag mappings. This process will rescan all content, so it could take some time to run. You generally do not need to rescan all content. If you have re-installed the Tag Plugin, rescanning should be done, othrwise, it should not be necessary.',
    'rescan_title' => 'Rescan All Content',
    'rescan_complete' => 'Rescanning of content has completed'
);

// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Tag-Plugin',
    'title' => 'Tag-Plugin-Konfiguration'
);

$LANG_confignames['tag'] = array(
    'default_block_name' => 'Standardname f�r Tag-Cloud-Block',
    'tag_name' => 'Tag-Name',
    'max_tag_len' => 'Max. L�nge eines tags in Bytes',
    'tag_case_sensitive' => 'Tag - Gro�-/Kleinschreibung',
    'tag_stemming' => 'Schlagworte erlauben',
    'tag_check_badword' => 'Liste mit Bad-Words verwenden',
    'tag_cloud_spacer' => 'String, als Abstandhalter in Tag-Cloud',
    'max_tag_cloud' => 'Max. Anzahl von Tags in Tag-Cloud',
    'max_tag_cloud_in_block' => 'Max. Anzahl von Tags im Tag-Cloud-Block',
    'tag_cloud_threshold' => 'Schwellwert der Tag-Levels',
    'replace_underscore' => 'Unterstrich mit Leerzeichen ersetzen',
    'num_keywords' => 'Max. Anzahl der Schl�sslw�rter',
    'publish_as_template_vars' => 'Ver�ffentliche Tags als Template-Vars',
    'displayblocks' => 'Display glFusion Blocks',
    'enable_whatsrelated' => 'Replace Story What\'s Related Block',
    'whatsrelated_limit' => 'Maximum number of items to return in What\'s Related block',
    'limit_related_types' => 'Allowed content types for What\'s Related (comma delimited list)'
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Haupteinstellungen'
);

$LANG_fs['tag'] = array(
    'fs_main' => 'Tag-Plugin - Haupteinstellungen'
);

$LANG_configSelect['tag'] = array(
    0 => array(true => 'Yes', false => 'No'),
    1 => array(0 => 'Left Blocks', 1 => 'Right Blocks', 2 => 'Left & Right Blocks', 3 => 'None')
);

?>