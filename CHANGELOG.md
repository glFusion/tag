Tag Plugin ChangeLog

##2.0.0
  - Major rewrite of functionality
  - Improved integration with all glFusion plugins
  - Ability to replace glFusion's story What's Related section with improved related stories based on tags
  - Removed tag menu feature
  - Improved styling to better integration with uikit based themes
  - New admin interface to allow better management of tags and ignored tags

##1.0.2 (unreleased)
  - Updated index sizes to allow better utf8 support

##1.0.1
  - Support for glFusion 1.3.0 AutoTag manager

##1.0.0
  - Implemented new glFusion admin authentication.
  - New configuration option to select with nav blocks display.
  - New plugin integration based on standard glFusion PLG_() APIs. This allows any plugin to take advantage of the features provided by the Tag Plugin
  - Implemented caching of some of the more costly SQL queries

##0.5.4
  - Fixed some permission issues.

##0.5.3
  - Added Dutch translation
  - Fixed issue where the template vars did not 'reset' if empty

##0.5.1
  - Implemented PLG_itemDeleted() API

##0.5.0
  - Implemented Media Gallery and Static Page support
  - Fixed issue where admin could not ban or delete a tag.
  - Tag menu did not work with URL rewrite enabled.
  - Fixed error where phantom tags could appear.