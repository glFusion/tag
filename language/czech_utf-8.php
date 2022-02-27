<?php
/**
* Tag Plugin for glFusion CMS
*
* English (UTF-8) Language File
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

$LANG_TAG = array(
    'plugin'            => 'Plugin tagu',
    'access_denied'     => 'Přístup odepřen',
    'access_denied_msg' => 'Přístup na tuto stránku má pouze root.  Tvé uživatelské jméno a IP adresa byly zaznamenány.',
    'admin'		        => 'admin pluginu tag',
    'install_header'	=> 'Instalovat/odinstalovat tag plugin',
    'install_success'	=> 'Instalace úspěšná',
    'install_fail'	    => 'Instalace se nezdařila -- Podívejte se na protokol chyb a zjistěte proč.',
    'uninstall_success'	=> 'Odinstalace byla úspěšná',
    'uninstall_fail'    => 'Instalace se nezdařila -- Podívejte se na protokol chyb a zjistěte proč.',
    'uninstall_msg'		=> 'Plugin tagu byl úspěšně odinstalován.',
    'version_required'  => 'Tag vyžaduje glFusion v1.1.0 nebo novější.',
    'tag_separators'    => ' ',	// Can be more than one character
    'badword_replace'   => '',
    'admin_label'       => 'Tag',
    'display_label'     => 'Tag: ',
    'default_block_title' => 'Populární tagy na této stránce',
    'tag_list'          => 'Seznam Tagů',
    'selected_tag'      => 'Tag se značkou <strong>%s</strong>: ',	// %s = tag name
    'related'           => 'Související Tagy',
    'block_title'       => 'Populární tagy na této stránce',
    'menu_stats'        => 'Seznam Tagů',
    'menu_badword'      => 'Ignorované Tagy',
    'db_error'          => 'Nelze číst z databáze.',
    'action'            => 'Akce',
    'desc_admin_stats'  => 'Toto je seznam registrovaných značek. V případě potřeby můžete ignorovat určité značky. Například pokud jsou příliš populární nebo obsahují nevhodná slova.',
    'lbl_tag'           => 'Tag',
    'lbl_count'         => 'Frekvence',
    'lbl_hit_count'     => 'Počet kliknutí',
    'delete_checked'    => 'Povolit zaškrtnuté štítky',
    'ban_checked'       => 'Ignorovat zkontrolované štítky',
    'desc_admin_badword' => 'Toto je seznam značek, které chcete ignorovat, například příliš populární nebo příliš vulgární značky.',
    'check'             => 'Kontrola',
    'add'               => 'Přidej',
    'edit'              => 'Editovat',
    'delete'            => 'Smazat',
    'submit'            => 'Vložit',
    'cancel'            => 'Storno',
    'badword'           => 'Ignorované tagy',
    'no_tag'            => 'Zatím nejsou definovány žádné štítky.',
    'no_badword'        => 'Zatím nejsou ignorovány žádné štítky.',
    'add_success'       => 'Úspěšně přidán.',
    'add_fail'          => 'Nelze přidat.',
    'delete_success'    => 'Úspěšně smazán.',
    'delete_fail'       => 'Nelze smazat.',
    'edit_success'      => 'Úspěšně upraveno.',
    'edit_fail'         => 'Nelze upravit.',
    'menu_title'        => 'Položky obsahující štítky: %s',
    'no_item'           => 'Nebyly nalezeny žádné odpovídající položky.',
    'no_title'          => 'Není k dispozici žádný název',
    'desc_tag'          => 'Odkaz na seznam všech položek obsahu označených tímto specifickým štítkem',
    'admin_help'        => 'Plugin "Tag plug" umožňuje vkládat "tagy" do vašeho obsahu (příběhy, stránky a popisy Media Gallery atd.) umožnit snadnější seskupování a načtení podobného obsahu.',
    'ignore_confirm'    => 'Opravdu chcete odstranit tento tag?',
    'unban_confirm'     => 'Opravdu chcete povolit tento štítek?',
    'menu_rescan'       => 'Znovu skenovat obsah',
    'cancel'            => 'Storno',
    'rescan'            => 'Znovu prohledat',
    'rescan_instructions' => 'Toto znovu naskenuje veškerý glFusion obsah a znovu sestaví Tagy. Tento proces znovu naskenuje veškerý obsah, takže může trvat nějaký čas. Obvykle nemusíte znovu naskenovat veškerý obsah. Pokud jste znovu nainstalovali Tag Plugin, opětovné skenování by mělo být hotovo, jinak by nemělo to být nutné.',
    'rescan_title'      => 'Znovu skenovat všechen obsah',
    'rescan_complete'   => 'Vyhledávání obsahu bylo dokončeno',
);

// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Plugin tagu',
    'title' => 'Konfigurace pluginu tagu'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
    'default_block_name'       => 'Výchozí název pro Tag Cloud Block',
    'tag_name'                 => 'Název tagu',
    'max_tag_len'              => 'Maximální délka tagu v bajtech',
    'tag_case_sensitive'       => 'Rozlišovat malá a velká písmena Tagu',
    'tag_stemming'             => 'Povolit kmenová slova',
    'tag_check_badword'        => 'Použít seznam nevhodných slov',
    'tag_cloud_spacer'         => 'Řetězec, který má být použit jako mezera v cloudu Tagů',
    'max_tag_cloud'            => 'Maximální počet štítků v cloudu štítků',
    'max_tag_cloud_in_block'   => 'Maximální počet Tagů v bloku cloud tagů',
    'tag_cloud_threshold'      => 'Prahová hodnota úrovní štítků',
    'replace_underscore'       => 'Nahradit podtržítko s mezerou',
    'num_keywords'             => 'Maximální počet klíčových slov',
    'publish_as_template_vars' => 'Publikovat štítky jako šablony variable',
    'displayblocks'            => 'Zobrazit bloky glFusion',
    'enable_whatsrelated'      => 'Nahradit článek v bloku- Co s ním souvisí',
    'whatsrelated_limit'       => 'Maximální počet položek k zobrazení v bloku Co s ním souvisí',
    'limit_related_types'      => 'Povolené typy obsahu pro blok Co s ním souvisí (čárkou oddělený seznam)',
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Hlavní'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'Hlavní nastavení zásuvného modulu tagu',
);

$LANG_configSelect['tag'] = array(
    0 => array(true => 'Ano', false => 'Ne'),
    1 => array(0 => 'Bloky nalevo', 1 => 'Bloky vpravo', 2 => 'Bloky nalevo a napravo', 3 => 'Žádný')
);

?>