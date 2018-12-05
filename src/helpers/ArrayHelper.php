<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\helpers;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class ArrayHelper extends \craft\helpers\ArrayHelper
{
    /**
     * Filters null values from an array.
     *
     * @param array $arr
     * @return array
     */
    public static function filterNullValuesFromArray(array $arr): array
    {
        return array_filter($arr, function ($value): bool {
            return $value !== null;
        });
    }

    /**
     * Filters null values from an array.
     *
     * @param array $arr
     * @return array
     */
    public static function filterEmptyAndNullValuesFromArray(array $arr): array
    {
        return array_filter($arr, function ($value): bool {
            return $value !== null && $value !== '';
        });
    }
}
