<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers\traits;

use craft\db\Query;
use craft\helpers\Db;
use craft\models\UserGroup;
use flipbox\ember\helpers\ArrayHelper;
use flipbox\ember\helpers\QueryHelper;
use craft\records\UserGroup as UserGroupRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait UserGroupQueryValue
{
    /**
     * @param $value
     * @param string $join
     * @return array
     */
    public static function parseUserGroupValue($value, string $join = 'and'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                self::resolveUserGroupValue($operator, $v);
            }
        }

        $value = ArrayHelper::filterEmptyAndNullValuesFromArray($value);

        if (empty($value)) {
            return [];
        }

        // parse param to allow for mixed variables
        return array_merge([$join], $value);
    }

    /**
     * @param $operator
     * @param $value
     */
    private static function resolveUserGroupValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = static::resolveUserGroupStringValue($value);
            }

            if ($value instanceof UserGroup) {
                $value = $value->id;
            }

            if ($value) {
                $value = QueryHelper::assembleParamValue($value, $operator);
            }
        }
    }

    /**
     * @param string $value
     * @return int|null
     */
    protected static function resolveUserGroupStringValue(string $value)
    {
        return (new Query())
            ->select(['id'])
            ->from([UserGroupRecord::tableName()])
            ->where(Db::parseParam('handle', $value))
            ->scalar();
    }
}
