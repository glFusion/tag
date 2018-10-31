<?php
/**
* Tag Plugin for glFusion CMS
*
* Database Schema
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

$_SQL['tag_list'] = "
CREATE TABLE {$_TABLES['tag_list']} (
  tag_id MEDIUMINT(10) NOT NULL AUTO_INCREMENT,
  tag VARCHAR(128) NOT NULL DEFAULT '',
  ignore_tag TINYINT UNSIGNED NOT NULL DEFAULT '0',
  hits MEDIUMINT(10) NOT NULL DEFAULT '0',
  PRIMARY KEY tag_id(tag_id)
) ENGINE=MyISAM
";

$_SQL['tag_list_index'] = "
  CREATE INDEX idx_tag_list_tag ON {$_TABLES['tag_list']} (tag)
";

$_SQL['tag_map'] = "
CREATE TABLE {$_TABLES['tag_map']} (
  map_id MEDIUMINT(10) NOT NULL AUTO_INCREMENT,
  tag_id MEDIUMINT(10) NOT NULL DEFAULT '0',
  type VARCHAR(30) NOT NULL DEFAULT 'article',
  sid VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY map_id(map_id)
) ENGINE=MyISAM
";

$_SQL['tag_map_index'] = "
  CREATE INDEX idx_tag_map_tag_id ON {$_TABLES['tag_map']} (tag_id)
";
?>