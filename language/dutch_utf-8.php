<?php
// +--------------------------------------------------------------------------+
// | Tag Plugin for glFusion                                                  |
// +--------------------------------------------------------------------------+
// | english.php                                                              |
// |                                                                          |
// | English Language File                                                    |
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
    'plugin'            => 'tag Plugin',
	'access_denied'     => 'Toegang Geweigerd',
	'access_denied_msg' => 'Alleen Root Gebruikers hebben toegang tot deze pagina.  Uw gebruikersnaam en IP adres zijn gelogd.',
	'admin'		        => 'tag Plugin Beheer',
	'install_header'	=> 'Installeer/Verwijder de tag Plugin',
	'install_success'	=> 'Installatie Succesvol',
	'install_fail'	    => 'Installatie Mislukt -- Bekijk uw fouten logboek om te zien waarom.',
	'uninstall_success'	=> 'Succesvol Verwijderd',
	'uninstall_fail'    => 'Installatie Mislukt -- Bekijk uw fouten logboek om te zien waarom.',
	'uninstall_msg'		=> 'Tag plugin is met succes verwijderd.',
	'tag_separators'    => ' ',	// Can be more than one character
	'badword_replace'   => '',
	'admin_label'       => 'Tag',
	'display_label'     => 'Tag: ',
	'default_block_title' => 'Populaire tags van deze website',
	'default_block_title_menu' => 'Tag Menu',
	'tag_list'          => 'Tag lijst',
	'selected_tag'      => 'Items met <strong>%s</strong> als tag: ',	// %s = tag name
	'related'           => 'Gerelateerde tags',
	'block_title'       => 'Populaire tags van deze website',
	'menu_stats'        => 'Statistieken',
	'menu_badword'      => 'Banned Tags',
	'menu_menuconfig'   => 'Tag Menu Instellingen',
	'db_error'          => 'Kan niet lezen van de database.',
	'action'            => 'Actie',
	'desc_admin_stats'  => 'Dit is de lijst met geregistreerde tags.  U kunt tags verwijderen of bannen (bijv. te populaire tags of ongewenste tags).',
	'lbl_tag'           => 'Tag',
	'lbl_count'         => 'Frequentie',
	'lbl_hit_count'     => 'Aantal clicks',
	'delete_checked'    => 'Verwijder geselecteerde tags',
	'ban_checked'       => 'Ban geselecteerde tags',
	'desc_admin_badword' => 'Dit is de lijst met tags die u in de ban wilt doen, bijvoorbeeld, te populaire tags of ongewenste tags.',
	'check'             => 'Selecteer',
	'add'               => 'Toevoegen',
	'edit'              => 'Wijzigen',
	'delete'            => 'Verwijderen',
	'submit'            => 'Opslaan',
	'cancel'            => 'Annuleren',
	'badword'           => 'Banned tags',
	'no_tag'            => 'Er is nog geen tag gedefinieerd.',
	'no_badword'        => 'Er staat nog geen tag op de ban lijst.',
	'desc_admin_menuconfig' => 'Dit zijn gedefinieerde menu items.',
	'no_parent'         => '(Geen)',
	'menu_name'         => 'Menu Naam',
	'menu_parent'       => 'Bovenliggend Menu',
	'menu_tags'         => 'Tags in menu',
	'menu_dsp_order'    => 'Volgorde van tonen',
	'desc_add_menu'     => 'Voeg een Tag Menu toe',
	'desc_edit_menu'    => 'Wijzige een Tag Menu',
	'desc_delete_menu'  => 'Delete Tag Menu',
	'add_child'         => 'Voeg een sub-menu toe',
	'order_up'          => 'Omhoog',
	'order_down'        => 'Omlaag',
	'add_success'       => 'Succesvol toegevoegd.',
	'add_fail'          => 'Kan niet toevoegen.',
	'delete_success'    => 'Succesvol verwijderd.',
	'delete_fail'       => 'Kan niet verwijderen.',
	'edit_success'      => 'Succesvol gewijzigd.',
	'edit_fail'         => 'Kan niet wijzigen.',
	'menu_title'        => 'Item(s) die de tag: %s bevat(ten)',
	'no_item'           => 'Geen items gevonden.',
);


// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Tag plugin',
    'title' => 'Tag plugin Instellingen'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
	'default_block_name'       => 'Standaard naam voor Tag Cloud Block',
	'tag_name'                 => 'Tag naam',
	'max_tag_len'              => 'Max lengte v/e tag in bytes',
	'tag_case_sensitive'       => 'Tag hoofdletter-gevoelig',
    'tag_stemming'             => 'Stemming woorden toestaan',
	'tag_check_badword'        => 'Gebruik lijst gecensureerde woorden',
	'tag_cloud_spacer'         => 'Karakter gebruikt als spatie in Tag Cloud',
	'max_tag_cloud'            => 'Max aantal tags in Tag Cloud',
	'max_tag_cloud_in_block'   => 'Max aantal tags in Tag Cloud Block',
	'tag_cloud_threshold'      => 'Max aantal Tag Levels',
	'replace_underscore'       => 'Vervang een underscore door een spatie',
	'num_keywords'             => 'Max aantal sleutelwoorden',
	'publish_as_template_vars' => 'Publiceer tags als template variabelen',
	'default_block_name_menu'  => 'Standaard naam voor Tag Menu Block',
	'menu_indenter'            => 'Karakter gebruikt als indenter in Tag Menu',
	'add_num_items_to_menu'    => 'Toon het aantal items in Tag Menu',
	'displayblocks'            => 'Display glFusion Blocks',

);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Hoofd'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'Tag plugin Hoofdinstellingen',
);

$LANG_configselects['tag'] = array(
    0 => array('Ja' => true, 'Nee' => false),
    1 => array('Left Blocks' => 0, 'Right Blocks' => 1, 'Left & Right Blocks' => 2, 'None' => 3)
);
?>