<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers\traits;

use Craft;
use craft\elements\User as UserElement;
use craft\helpers\ArrayHelper;
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
                self::resolveUserValue($operator, $v);
            }
        }

        // parse param to allow for mixed variables
        return array_merge([$join], ArrayHelper::filterEmptyStringsFromArray($value));
    }

    /**
     * @param $operator
     * @param $value
     */
    private static function resolveUserValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                if ($element = Craft::$app->getUsers()->getUserByUsernameOrEmail($value)) {
                    $value = $element->id;
                }
            }

            if ($value instanceof UserElement) {
                $value = $value->id;
            }

            if ($value) {
                $value = QueryHelper::assembleParamValue($value, $operator);
            }
        }
    }
}
