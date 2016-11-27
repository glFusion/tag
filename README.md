## Tag Plugin for glFusion

For the latest, and more detailed, documentation, please see the [Tag Plugin Wiki Page](https://www.glfusion.org/wiki/glfusion:plugins:tag:start)

### LICENSE

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

### OVERVIEW

The Tag plugin enables you to put "tags" to your content and make it easier for you and visitors to classify content using the tags.

### SYSTEM REQUIREMENTS

The Tag Plugin has the following system requirements:

* PHP 5.3.3 and higher.
* glFusion v1.6.3 or newer

### INSTALLATION

The Tag Plugin uses the glFusion automated plugin installer. Simply upload the distribution using the glFusion plugin installer located in the Plugin Administration page.

### UPGRADING

Tag v2.0.0 is a complete rewrite which requires some special upgrade handling when upgrading from earlier versions.

Once you have uploaded the latest Tag Plugin through the glFusion Plugin Administration screen and selected upgrade, you will need to do the following:

1 .Navigate to the Command & Control -> Tag administration screen.
2. Select **Rescan Content**. This will rescan all glFusion content and rebuild the tag mappings.

Once you have completed the steps above, you should be ready to go.

### CONFIGURATION

The Tag configuration option are available in the Configuration section located in  Command & Control.

### Default name for Tag Cloud Block

The default name of tag block which will be created during the installation. If you  disable/enable the tag plugin, the block will also be disabled/enabled automatically.


### Tag Name

Tag name to be used in items (articles), like '[tag:foo]'. You might prefer a shorter name like '[t:foo]'.

### Maximum length of a tag in characters

Maximum number of characters allowed in a tag. Should not be longer than 128 characters.


### Tag Case sensitive

If this is true, the tag "glFusion" will NOT be identified with the tag "glfusion". When you change this option, you should re-install tag plugin.


### Allow stemming words

If this is true, each tag consisting only of alphabets will be stemmed. For example, tag "realize" will be stemmed into "real", thus tag "realize" will be identified with tag "real".

**WARNING:** The stemming feature is still not perfect. For example, 'Firefox' is stemmed into 'Firefoxi'. So, I don't recommend you set Allow Stemming Words to true for the time being (and forever, maybe).

### Use list of ignored words

Whether to use a list of ignored words. If a tag is regarded as ignored, it will be replaced with the text specified in the **blank** automatically.

### String to be used as spacer in Tag Cloud

A string to be used as a spacer in displaying tag clouds

### Max number of tags in Tag Cloud

Maximum number of tags to be displayed in tag clouds in public_html/tag/index.php

### Max Number of tgas in Tag Cloud Block

Max number of tags to be displayed in tag clouds in side block

### Threshold of Tag Levels

Thresholds of frequency of each tag cloud level. All tag clouds are classified in 10 levels (level 0..level 9). Those tags whose number is equal to or smaller than the  threshold setting [X] belong to level X. Each level corresponds to its own class in CSS (Cascading Style Sheet), so you can display in different styles tags according to their levels.

### Replace an underscore with a space

Whether to replace an underscore included in tag texts with a space when a tag is displayed.

### Max number of keywords

The number of key words to be included in <meta name="keywords" content="foo,bar"> tag. If the number is 0, the meta tag won't be included.

### Publish tags as template vars

If this is set to true, tags will NOT be displayed where they should be in the article, but will be published as template vars ({tag_label} and {tag_part}) which can be used in 'storytext.thtml', 'featuredstorytext.thtml', and 'archivestorytext.thtml'. If this is set to false, tags will be displayed in the article.

### Display glFusion Blocks

Defines which blocks should be displayed (i.e.; left / right ) when displaying the tag interface.

### Replace Story What's Related 

If this is enabled, the Tag plugin will generate a list of content items (not limited to just stories) that are related based on the tags in other content. This provides a much better set of related items than the default What's Related.

### Maximum number of items to return in What's Related

This is the maximum number of items to return in the What's Related section.


## USAGE

When you are writing an article or other glFusion content and want to put tags to it, just enter:

    [tag:your_favorite_tag]

in the article. You can use multiple tags like this:

    [tag:glFusion plugins]

Each tag must be separated by a space. If you would like to register words as
one tag, such as "Zend Framework", you should enter "Zend_Framework". By
default, tags are case-insensitive. For example, the tag "glfusion" will
be identified with "glfusion" or "GLFUSION".

If you want to ignore certain tags, you can do so in the Tag Administration.

Ignore words are also case-insensitive.

If you would like to display "tag clouds" in your site block, just create a
PHP block with the function name being phpblock_tag_cloud.  This block is
create automatically during the plugin installation.

The apperance of tag clouds are controlled with templates
  (in <glfusion>/plugins/tag/templates/) and Cascading Style Sheet
  (as <public_html>/tag/tag.css).

  In case <public_html>/layout/your_theme/tag.css exists, this will be
  preferred over <public_html>/tag/tag.css.

