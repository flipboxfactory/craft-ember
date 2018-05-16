Changelog
=========

## Unreleased
### Changed
- Save action traits populate the object/record/element prior to checking access.

## 1.0.3 - 2018-05-08
### Added
- \flipbox\ember\data\ActiveDataProvider class to perform clone operations including behaviours

## 1.0.2.1 - 2018-05-03
### Added
- trace and info level logging also evaluates `YII_DEBUG` 

## 1.0.2 - 2018-05-03
### Added
- `\flipbox\ember\modules\LoggerTrait` class to assist with plugin level logging to a separate file

## 1.0.1 - 2018-04-25
### Added
- MinMaxValidator which extends Craft's MinMaxValidator except it also works with query value attributes

## 1.0.0 - 2018-04-24
- Production release

## 1.0.0-rc.18 - 2018-04-04
### Added
- `flipbox\ember\db\traits\ElementAttribute`

## 1.0.0-rc.17 - 2018-03-29
### Added
- Record base actions

## 1.0.0-rc.16 - 2018-03-20
### Fixed
- Incorrect condition when checking instance on ObjectHelper::create
- Trait conflict with ElementView::runInternal

## 1.0.0-rc.15 - 2018-03-20
### Added
- ActiveRecord::create trait method to create a new ActiveRecord
- Element Accessor traits

### Changed
- Removed various deprecations

## 1.0.0-rc.14 - 2018-03-01
### Changed
- Legacy translation categories

## 1.0.0-rc.13 - 2018-03-01
### Added
- BaseAccessor service query trait

## 1.0.0-rc.12 - 2018-02-22
### Added
- NotFoundException to indicate a generic not found error
- New accessor traits

### Changed
- Deprecated all existing accessor traits in favor of a more optimized query approach

## 1.0.0-rc.11 - 2018-02-18
### Removed
- RecordHelper::configure, RecordHelper::create and RecordHelper::populate as they're code smell
- ElementHelper due to code smell
- SiteHelper::get due to code smell

## 1.0.0-rc.10 - 2018-02-16
### Added
- ArrayHelper::insertSequential to assist in injecting a key within sequentially ordered values.

## 1.0.0-rc.9 - 2018-02-13
### Added
- InvalidQueryException
- Query Helper traits to assist with resolving user/user group params
- ActiveRecord service accessor traits
- ArrayHelper to assist with filtering null/empty array values
- CacheableActiveQuery, similar to an ElementQuery
- FieldLayoutHelper to assist with resolving a FieldLayout
- Site and FieldLayout traits

### Changed
- Exceptions now inherit Yii's base Exception, not ErrorException
- Deprecated ElementNotFoundException in favor of Craft's first party exception.
- Creating an object from a record now considers any record relations populated.
- Enhancing helper traits for easier overrides
- ModelValidator now accepts an array of models

## 1.0.0-rc.8 - 2018-01-31
### Added
- ElementAccessor traits for element centric services
- Manage element action trait
- SiteHelper::get to assist in resolving site models
- ModelErrorFilter has a 'returnNullOnError' property to allow returning null when an error is found
- QueryHelper functions to assist with normalizing a db query

### Changed
- Create/Update element actions are specific to elements
- CheckAccess trait now throws an UnauthorizedHttpException

### Fixed
- Incorrect caching on ElementAccessor traits

## 1.0.0-rc.7 - 2018-01-17
### Changed
- Index actions are more flexible when creating the data provider

## 1.0.0-rc.6 - 2017-12-15
### Added
- Clipboard Asset Bundle
- Reveal Asset Bundle
- RowInfo Asset Bundle

### Changed
- Card / CircleIcon asset (css) updates

## 1.0.0-rc.5 - 2017-12-13
### Added
- Model validator for validation sub-models

### Changed
- ModelHelper now supports `yii\base\Model`

## 1.0.0-rc.4.1 - 2017-12-12
### Fixed
- Action returning a false positive on checks

## 1.0.0-rc.4 - 2017-12-10
### Added
- ModelError filter
- Filter traits

## 1.0.0-rc.3 - 2017-12-09
### Added
- FlashMessages filter

### Changed
- Added additional parameters to RedirectFilter for more granular control

## 1.0.0-rc.2 - 2017-12-06
### Added
- Card asset bundle
- HUD asset bundle
- Circle Icon asset bundle
- Elements asset bundle

## 1.0.0-rc.1.1 - 2017-12-05
### Changed
- Min requirement of Craft RC1

### Added
- PageTemplate view to render a page template (including asset bundles, etc).

## 1.0.0-rc.1 - 2017-11-26
### Added
- Requirement for Craft RC1 release

## 1.0.0-beta - 2017-03-22
### Added
- Initial release.
