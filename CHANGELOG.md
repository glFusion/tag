# Tag Plugin ChangeLog

## v2.1.3

- Czech translation
- Fixed configuration issue on glFusion LTS releases
- Fixed issue where a &nbsp; instead of physical space could cause tags to not be split

## v2.1.2

- Several minor code tweaks to address SQL errors

## v2.1.1

- Configuration settings where not always honored

## v2.1.0

- Ignored tags were included in what's related
- Rewrite of many internal routines to bring up-to-date and improve ignore tag handling
- Fixed broken admin functions of add / remove ban words
- Do not display an empty tag cloud block
- Added self checks to not process PLG calls for tag functions

## v2.0.2

- UI Updates - HTML 5 compatibility fixes
- Fixed uninitialized variable error

## v2.0.1

- Default tag to tag with most hits when no tag is passed
- Support for custom language files
- Fixed incorrect menu display after ignoring a tag
- Improved error handling when rescanning content
- Do not display tag cloud if limit is 0
- SQL cleanup - reformat to use standard format in glFusion
- Removed tag stemming option

## v2.0.0

- Major rewrite of functionality
- Improved integration with all glFusion plugins
- Ability to replace glFusion's story What's Related section with improved related stories based on tags
- Removed tag menu feature
- Improved styling to better integration with uikit based themes
- New admin interface to allow better management of tags and ignored tags

## v1.0.2

- Updated index sizes to allow better utf8 support

## v1.0.1

- Support for glFusion 1.3.0 AutoTag manager

## v1.0.0

- Implemented new glFusion admin authentication.
- New configuration option to select with nav blocks display.
- New plugin integration based on standard glFusion PLG_() APIs. This allows any plugin to take advantage of the features provided by the Tag Plugin
- Implemented caching of some of the more costly SQL queries

## v0.5.4

- Fixed some permission issues.

## v0.5.3

- Added Dutch translation
- Fixed issue where the template vars did not 'reset' if empty

## v0.5.1

- Implemented PLG_itemDeleted() API

## v0.5.0

- Implemented Media Gallery and Static Page support
- Fixed issue where admin could not ban or delete a tag.
- Tag menu did not work with URL rewrite enabled.
- Fixed error where phantom tags could appear.
