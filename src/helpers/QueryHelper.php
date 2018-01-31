<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;
use yii\db\Query;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class QueryHelper
{
    /**
     * @var array
     */
    protected static $operators = ['not ', '!=', '<=', '>=', '<', '>', '='];

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
     * Standard param parsing.
     *
     * @param $value
     * @param $join
     * @return bool
     */
    public static function parseBaseParam(&$value, &$join): bool
    {
        // Force array
        if (!is_array($value)) {
            $value = [$value];
        }

        // Get join type ('and' , 'or')
        $join = static::getJoinType($value, $join);

        // Check for object array (via 'id' key)
        if ($id = static::findIdFromObjectArray($value)) {
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
     */
    public static function findParamValue(&$value, &$operator): bool
    {

        if (is_array($value) || is_object($value)) {
            $value = static::assembleParamValue($value, $operator);
        } else {
            static::normalizeEmptyValue($value);

            $operator = static::parseParamOperator($value);

            if (is_numeric($value)) {
                $value = static::assembleParamValue($value, $operator);
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
     * @return string
     */
    public static function assembleParamValue($value, $operator)
    {

        // Handle arrays as values
        if (is_array($value) || is_object($value)) {
            // Look for an 'id' key in an array
            if ($id = static::findIdFromObjectArray($value, $operator)) {
                // Prepend the operator
                return static::prependOperator($id, $operator);
            }
        }

        return static::prependOperator($value, $operator);
    }

    /**
     * Attempt to resolve a param value by the value.
     * Return false if a 'handle' or other string identifier is detected.
     *
     * @param $value
     * @param $operator
     * @return bool
     */
    public static function prepParamValue(&$value, &$operator): bool
    {

        if (is_array($value)) {
            return true;
        } else {
            static::normalizeEmptyValue($value);
            $operator = static::parseParamOperator($value);

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
     */
    private static function findIdFromObjectArray($value, $operator = null)
    {
        if ($id = ArrayHelper::getValue($value, 'id')) {
            return static::prependOperator($id, $operator);
        }

        return $id;
    }

    /**
     * Prepend the operator to a value
     *
     * @param $value
     * @param null $operator
     * @return string|array
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
