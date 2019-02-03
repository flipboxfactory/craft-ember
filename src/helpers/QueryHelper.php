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


    /**
     * Standard param parsing.
     *
     * @param $value
     * @param $join
     * @return bool
     *
     * @deprecated
     */
    public static function parseBaseParam(&$value, &$join): bool
    {
        // Force array
        if (!is_array($value)) {
            $value = [$value];
        }

        // Get join type ('and' , 'or')
        $join = self::getJoinType($value, $join);

        // Check for object array (via 'id' key)
        if ($id = self::findIdFromObjectArray($value)) {
            $value = [$id];
            return true;
        }

        return false;
    }

    /**
     * Attempt to resolve a param value by the value.
     * Return false if a 'handle' or other string identifier is detected.
     *
     * @param $value
     * @param $operator
     * @return bool
     *
     * @deprecated
     */
    public static function findParamValue(&$value, &$operator): bool
    {
        if (is_array($value) || is_object($value)) {
            $value = static::assembleParamValue($value, $operator);
        } else {
            self::normalizeEmptyValue($value);

            $operator = self::parseParamOperator($value);

            if (is_numeric($value)) {
                $value = self::prependOperator($value, $operator);
            } else {
                $value = StringHelper::toLowerCase($value);

                if ($value !== ':empty:' || $value !== 'not :empty:') {
                    // Trim any whitespace from the value
                    $value = StringHelper::trim($value);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Format the param value so that we return a string w/ a prepended operator.
     *
     * @param $value
     * @param $operator
     * @param string|int|mixed $defaultValue
     * @return array|string
     *
     * @deprecated
     */
    public static function assembleParamValue($value, $operator, $defaultValue = ':default:')
    {
        if (is_array($value) || is_object($value)) {
            $id = self::findIdFromObjectArray($value, $operator);

            if ($id !== null) {
                return self::prependOperator($id, $operator);
            }

            if (is_object($value)) {
                return $defaultValue;
            }
        }

        return self::prependOperator($value, $operator);
    }

    /**
     * Attempt to resolve a param value by the value.
     * Return false if a 'handle' or other string identifier is detected.
     *
     * @param $value
     * @param $operator
     * @return bool
     *
     * @deprecated
     */
    public static function prepParamValue(&$value, &$operator): bool
    {

        if (is_array($value)) {
            return true;
        } else {
            self::normalizeEmptyValue($value);
            $operator = self::parseParamOperator($value);

            if (is_numeric($value)) {
                return true;
            } else {
                $value = StringHelper::toLowerCase($value);

                if ($value !== ':empty:' || $value !== 'not :empty:') {
                    // Trim any whitespace from the value
                    $value = StringHelper::trim($value);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $value
     * @param string $default
     * @return mixed|string
     *
     * @deprecated
     */
    private static function getJoinType(&$value, $default = 'or')
    {

        // Get first value in array
        $joinType = ArrayHelper::firstValue($value);

        // Make sure first value is a string
        $firstVal = is_string($joinType) ? StringHelper::toLowerCase($joinType) : '';

        if ($firstVal == 'and' || $firstVal == 'or') {
            $join = array_shift($value);
        } else {
            $join = $default;
        }

        return $join;
    }

    /**
     * Attempt to get a numeric value from an object array.
     * @param $value
     * @param null $operator
     * @return mixed|string
     *
     * @deprecated
     */
    private static function findIdFromObjectArray($value, $operator = null)
    {
        if ($id = ArrayHelper::getValue($value, 'id')) {
            return self::prependOperator($id, $operator);
        }

        return $id;
    }

    /**
     * Prepend the operator to a value
     *
     * @param $value
     * @param null $operator
     * @return string|array
     *
     * @deprecated
     */
    private static function prependOperator($value, $operator = null)
    {

        if ($operator) {
            $operator = StringHelper::toLowerCase($operator);

            if (in_array($operator, static::$operators) || $operator === 'not') {
                if (is_array($value)) {
                    $values = [];

                    foreach ($value as $v) {
                        $values[] = $operator . ($operator === 'not' ? ' ' : '') . $v;
                    }

                    return $values;
                }

                return $operator . ($operator === 'not' ? ' ' : '') . $value;
            }
        }

        return $value;
    }

    /**
     * Normalizes “empty” values.
     *
     * @param string &$value The param value.
     *
     * @deprecated
     */
    private static function normalizeEmptyValue(&$value)
    {
        if ($value === null) {
            $value = ':empty:';
        } else {
            if (StringHelper::toLowerCase($value) == ':notempty:') {
                $value = 'not :empty:';
            }
        }
    }

    /**
     * Extracts the operator from a DB param and returns it.
     *
     * @param string &$value Te param value.
     *
     * @return string The operator.
     *
     * @deprecated
     */
    private static function parseParamOperator(&$value)
    {
        foreach (static::$operators as $testOperator) {
            // Does the value start with this operator?
            $operatorLength = strlen($testOperator);

            if (strncmp(
                StringHelper::toLowerCase($value),
                $testOperator,
                $operatorLength
            ) == 0
            ) {
                $value = mb_substr($value, $operatorLength);

                if ($testOperator == 'not ') {
                    return 'not';
                } else {
                    return $testOperator;
                }
            }
        }

        return '';
    }
}
