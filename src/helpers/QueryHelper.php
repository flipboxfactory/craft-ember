<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\helpers;

use craft\helpers\StringHelper;
use yii\db\Query;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class QueryHelper
{
    /**
     * @var array
     */
    protected static $operators = ['not ', '!=', '<=', '>=', '<', '>', '='];

    /**
     * @param $condition
     * @return array
     */
    public static function conditionToCriteria($condition)
    {
        if (empty($condition)) {
            return $condition;
        }

        // Assume it's an id
        if (!is_array($condition)) {
            $condition = [
                'id' => $condition
            ];
        }

        return ['where' => ['and', $condition]];
    }

    /**
     * @param QueryInterface|Query $query
     * @param array $config
     * @return QueryInterface
     */
    public static function configure(QueryInterface $query, $config = []): QueryInterface
    {
        // Halt
        if (empty($config)) {
            return $query;
        }

        // Force array
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        // Populate query attributes
        foreach ($config as $name => $value) {
            if ($query->canSetProperty($name)) {
                $query->$name = $value;
            }
        }

        return $query;
    }

    /**
     * Prepares a query params input value to be used as a condition.  This will attempt to resolve an object
     * or look one up based on a 'handle' or other unique string identifier (via the lookup callable).
     *
     * ```php
     *
     * [
     *      'and',
     *      'not' => [
     *          4, elementFiveHandle, $someElementWithIdOfSix
     *      ]
     * ]
     * ```
     * Would result in a query condition as `NOT IN (4, 5, 6)`.
     *
     * @param $value
     * @param callable|null $lookup
     * @return array|string
     */
    public static function prepareParam($value, callable $lookup = null)
    {
        if (!is_array($value)) {
            // An object (model, element, record)
            if (is_object($value)) {
                // Try to grab the Id from it
                try {
                    return $value->id ?: false;
                } catch (\Exception $e) {
                    // Carry on
                }
            }

            // alpha identifier (handle, etc)
            if (is_string($value) &&
                !in_array($value, ['and', 'or'], true) &&
                StringHelper::isAlpha($value)
            ) {
                if (null !== $lookup) {
                    if (null !== ($val = call_user_func($lookup, $value))) {
                        return static::prepareParam($val, $lookup);
                    }
                }
            }

            return $value;
        }

        // Traverse
        $return = [];
        foreach ($value as $key => $val) {
            $return = ArrayHelper::merge(
                $return,
                static::prepareParamValue($key, $val, $lookup)
            );
        }

        return $return;
    }

    /**
     * @param $key
     * @param $value
     * @param callable|null $lookup
     * @return array
     */
    protected static function prepareParamValue($key, $value, callable $lookup = null): array
    {
        $value = static::prepareParam($value, $lookup);

        // Move arrays up one level
        if (is_array($value)) {
            $values = [];

            $firstVal = strtolower(reset($value));

            foreach ($value as $k => $val) {
                $values = ArrayHelper::merge(
                    $values,
                    static::prepareParamValue(
                        is_numeric($k) ? $key : $k,
                        $val,
                        $lookup
                    )
                );
            }

            if (in_array($firstVal, ['and', 'or'], true)) {
                return [$values];
            }

            return $values;
        }

        if (!is_numeric($key)) {
            if (is_string($value) || is_numeric($value)) {
                $value = $key . ' ' . $value;
            }
        }

        return [$value];
    }
}
