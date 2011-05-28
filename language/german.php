<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | german_utf-8.php                                                         |
// |                                                                          |
// | German Language File (UTF-8)                                             |
// | Modifiziert: August 09 Tony Kluever									  |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Based on the Tag Plugin for Geeklog CMS                                  |
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

$LANG_TAG = array(
    'plugin'            => 'Tag-Plugin',
	'access_denied'     => 'Zugriff verweigert',
	'access_denied_msg' => 'Nur Root-Benutzer haben Zugriff auf diese Seite. Dein Benutzername und IP wurden aufgezeichnet.',
	'admin'		        => 'Tag-Plugin Administration',
	'install_header'	=> 'Tag-Plugin Installation/Deinstallation',
	'install_success'	=> 'Installation erfolgreich',
	'install_fail'	    => 'Installation fehlgeschlagen -- Schau in die Datei error.log f�r mehr Infos.',
	'uninstall_success'	=> 'Deinstallation erfolgreich',
	'uninstall_fail'    => 'Deinstallation fehlgeschlagen -- Schau in die Datei error.log f�r mehr Infos.',
	'uninstall_msg'		=> 'Tag-Plugin wurde erfolgreich installiert.',
	'version_required'  => 'Tag ben�tigt glFusion v1.1.0 oder h�her.',
	'tag_separators'    => ' ',	// Can be more than one character
	'badword_replace'   => '',
	'admin_label'       => 'Tag',
	'display_label'     => 'Tag: ',
	'default_block_title' => 'Beliebte Tags auf dieser Seite',
	'default_block_title_menu' => 'Tag-Men�',
	'tag_list'          => 'Tag-Liste',
	'selected_tag'      => 'Objekte, die <strong>%s</strong> Tag haben: ',	// %s = tag name
	'related'           => '�hnliche Tags',
	'block_title'       => 'Bleiebte Tags auf dieser Seite',
	'menu_stats'        => 'Statistiken',
	'menu_badword'      => 'Gesperrte Tags',
	'menu_menuconfig'   => 'Tag-Men� - Konfiguration',
	'db_error'          => 'Kann nicht von Datenbank lesen.',
	'action'            => 'Aktion',
	'desc_admin_stats'  => 'Dies ist die Liste der registrierten Tags. Du kannst falsch registrierte Tags l�schen oder Tags bannen (z.B. zu beliebte oder vulg�re Tags).',
	'lbl_tag'           => 'Tag',
	'lbl_count'         => 'Frequenz',
	'lbl_hit_count'     => 'Anzahl der Klicks',
	'delete_checked'    => 'Gepr�ften Tag l�schen',
	'ban_checked'       => 'Gepr�fte Tags sperren',
	'desc_admin_badword' => 'Dies ist die Liste der Tags, die Du sperren willst, zum Beispiel, zu beliebte oder vulg�re Tags.',
	'check'             => 'Pr�fen',
	'add'               => 'Hinzuf�gen',
	'edit'              => 'Bearbeiten',
	'delete'            => 'L�schen',
	'submit'            => 'Senden',
	'cancel'            => 'Abbruch',
	'badword'           => 'Gesperrte Tags',
	'no_tag'            => 'Noch keine Tags definiert.',
	'no_badword'        => 'Noch keine Tags gesperrt.',
	'desc_admin_menuconfig' => 'Dies sind definierte Men�objekte.',
	'no_parent'         => '(Kein)',
	'menu_name'         => 'Men�name',
	'menu_parent'       => '�bergeordnetes Men�',
	'menu_tags'         => 'Enthaltene Tags',
	'menu_dsp_order'    => 'Sortierungsanzeige',
	'desc_add_menu'     => 'Tag-Men� hinzuf�gen',
	'desc_edit_menu'    => 'Tag-Men� bearbeiten',
	'desc_delete_menu'  => 'Tag-Men� l�schen',
	'add_child'         => 'Untermen� hinzuf�gen',
	'order_up'          => 'Hoch',
	'order_down'        => 'Runter',
	'add_success'       => 'Erfolgreich hinzugef�gt.',
	'add_fail'          => 'Kann nicht hinuf�gen.',
	'delete_success'    => 'Erfolgreich gel�scht.',
	'delete_fail'       => 'Kann nicht l�schen.',
	'edit_success'      => 'Erfolgreich ge�ndert.',
	'edit_fail'         => 'Kann nicht �ndern.',
	'menu_title'        => 'Objekte die Tags enthalten: %s',
	'no_item'           => 'Keine passenden Objekte gefunden.',
	'no_title'			=> 'Kein Titel verf�gbar',
	'desc_tag'          => 'Link to list of all content items flagged with this specific tag',
);


// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Tag-Plugin',
    'title' => 'Tag-Plugin-Konfiguration'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
	'default_block_name'       => 'Standardname f�r Tag-Cloud-Block',
	'tag_name'                 => 'Tag-Name',
	'max_tag_len'              => 'Max. L�nge eines tags in Bytes',
	'tag_case_sensitive'       => 'Tag - Gro�-/Kleinschreibung',
    'tag_stemming'             => 'Schlagworte erlauben',
	'tag_check_badword'        => 'Liste mit Bad-Words verwenden',
	'tag_cloud_spacer'         => 'String, als Abstandhalter in Tag-Cloud',
	'max_tag_cloud'            => 'Max. Anzahl von Tags in Tag-Cloud',
	'max_tag_cloud_in_block'   => 'Max. Anzahl von Tags im Tag-Cloud-Block',
	'tag_cloud_threshold'      => 'Schwellwert der Tag-Levels',
	'replace_underscore'       => 'Unterstrich mit Leerzeichen ersetzen',
	'num_keywords'             => 'Max. Anzahl der Schl�sslw�rter',
	'publish_as_template_vars' => 'Ver�ffentliche Tags als Template-Vars',
	'default_block_name_menu'  => 'Standardname f�r Tag-Men�-Block',
	'menu_indenter'            => 'String, als Stempel in Tag-Men�',
	'add_num_items_to_menu'    => 'Anzahl der Objekte im tag-Men� anzeigen',
	'displayblocks'            => 'Display glFusion Blocks',

);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Haupteinstellungen'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'Tag-Plugin - Haupteinstellungen',
);

$LANG_configselects['tag'] = array(
    0 => array('Ja' => true, 'Nein' => false),
    1 => array('Left Blocks' => 0, 'Right Blocks' => 1, 'Left & Right Blocks' => 2, 'None' => 3)
);
?>