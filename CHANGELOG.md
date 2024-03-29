Changelog
=========

## 3.0.1 - 2023-07-27
### Changed
- Promoting the `loggerCategory` method to public so it can be used elsewhere

## 3.0.0 - 2023-01-20
### Fixed
- Craft 4 compatibility

## 2.6.5 - 2020-02-24
### Fixed
- Lingering PHP 8 (level 0) warnings and errors

## 2.6.4 - 2020-02-23
### Fixed
- Compatibility issues w/ PHP 8

## 2.6.3 - 2020-01-10
### Fixed
- Issue with logging via console.

## 2.6.2 - 2020-01-08
### Added
- `LoggerHelper::$requireSession` which can be turned off for stateless logging

## 2.6.1 - 2019-12-04
### Changed
- Relaxing object trait id comparisons from `!==` to `==`

## 2.6.0 - 2019-11-25
### Fixed
- Action::checkAccess now throws a ForbiddenHttpException instead of a UnauthorizedHttpException.

## 2.5.3 - 2019-10-30
### Fixed
- When passing an empty array to `Record::findAll([])` an improper condition would be built.

## 2.5.2 - 2019-10-15
### Fixed
- `CheckAccessTrait::handleUnauthorizedResponse()` was passing the message and code incorrectly.

## 2.5.1 - 2019-10-03
### Fixed
- `ActiveQuery::andWhere()` would attempt to set query attribute values, however this resulted in some conditions never being set.

## 2.5.0 - 2019-07-19
### Changed
- Various object mutation traits have abstract methods to make them more compatible with models and records.  Using
the mutation traits directly will require implementing the abstract methods.

### Removed
- Deprecated QueryHelper methods that would result in poorly created conditions.

## 2.4.1 - 2019-06-20
### Added
- Object checks to see if an object has been set for a relation

## 2.4.0 - 2019-06-18
### Added
- Module logger can accept an 'audit' flag when logging which adds another category that can be targeted and handled uniquely.
- The Active Record `SortableTrait` can ignore sorting by chaining `Record::ignoreSortOrder()` prior to the record `save`.

## 2.3.2 - 2019-05-13
### Removed
- Yii2 app requirement (let Craft set this)

## 2.3.1 - 2019-04-28
### Added
- EmailByKey queue job
- ElementQueryOptionsTrait::$queryConfig can be a string or an array

### Changed
- Query attribute traits will throw an exception if parsing an object results in false

### Fixed
- Fat fingered comment in the LoggerTrait

## 2.3.0 - 2019-03-17
### Added
- CLI traits to assist with debug, query, and queueable actions

### Changed 
- The module LoggerTrait has been simplified and now requires a static $category attribute set

## 2.2.1 - 2019-02-02
### Changed
- When resolving an object value via QueryHelper, return false if the Id is not set.

## 2.2.0 - 2019-01-24
### Added
- `QueryHelper::prepareParam` to simplify mixed query param handling

### Removed
Deprecated `QueryHelper::parseBaseParam` and all supporting methods

## 2.1.0 - 2019-01-10
### Added
- Cachable queries can clear cached results
- Element trait adding explicit `Element::get()` and `Element::getAll()` methods

### Fixed
- Auto-reording would throw an error when there was nothing to re-order.

## 2.0.0 - 2018-12-06
### Changed
- Namespace and various classes/traits.

### Removed
- Various excessive classes/traits.

## 1.0.8 - 2018-10-09
### Added
- `\flipbox\ember\controllers\LogViewerTrait` to assist w/ consuming and digesting log files

## 1.0.7 - 2018-07-17
### Changed
- `\flipbox\ember\services\traits\queries\Accessor::findAllByCondition` will attempt to set query property values prior to creating a 'where' condition.

### Removed
- `CircleIcon` and `Card` asset bundles.

## 1.0.6 - 2018-07-06
### Changed
- ActiveQuery accessor methods will catch QueryAbortedExceptions similar to native Craft Query accessor methods.

## 1.0.5 - 2018-06-21
### Fixed
- Post functions that were introduced by [Craft 3.0.10](https://github.com/craftcms/cms/commit/6b446e83dd2bd426c269c893d446dbaaef2bae74#diff-9fb0aaf8b328a3f55c1157c12692e1ee)

## 1.0.4 - 2018-05-16
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
