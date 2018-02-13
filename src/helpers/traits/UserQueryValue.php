<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers\traits;

use Craft;
use craft\elements\User as UserElement;
use flipbox\ember\helpers\ArrayHelper;
use flipbox\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait UserQueryValue
{
    /**
     * @param $value
     * @param string $join
     * @return array
     */
    public static function parseUserValue($value, string $join = 'and'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                static::resolveUserValue($operator, $v);
            }
        }

        $value = ArrayHelper::filterEmptyAndNullValuesFromArray($value);

        if (empty($value)) {
            return [];
        }

        return array_merge([$join], $value);
    }

    /**
     * @param $operator
     * @param $value
     */
    protected static function resolveUserValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = self::resolveUserStringValue($value);
            }

            if ($value instanceof UserElement) {
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
    protected static function resolveUserStringValue(string $value)
    {
        if (!$element = Craft::$app->getUsers()->getUserByUsernameOrEmail($value)) {
            return null;
        }
        return $element->id;
    }
}
