<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ArrayHelper extends \craft\helpers\ArrayHelper
{
    /**
     * Filters null values from an array.
     *
     * @param array $arr
     *
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
     *
     * @return array
     */
    public static function filterEmptyAndNullValuesFromArray(array $arr): array
    {
        return array_filter($arr, function ($value): bool {
            return $value !== null && $value !== '';
        });
    }

    /**
     * @param array $sourceArray The source array which the target is to be inserted into.  The
     * key represents a unique identifier, while the value is the sort order.
     *
     * As an example if this is the $sourceArray
     *
     * ```
     * [
     *      111 => 1,
     *      343 => 2,
     *      545 => 3,
     *      'foo' => 4,
     *      'bar' => 5
     * ]
     * ```
     *
     * And your $targetKey is 'fooBar' with a $targetOrder of 4, the result would be
     *
     * ```
     * [
     *      111 => 1,
     *      343 => 2,
     *      545 => 3,
     *      'fooBar' => 4,
     *      'foo' => 5,
     *      'bar' => 6
     * ]
     * ```
     *
     * @param string|int $targetKey
     * @param int $targetOrder
     * @return array|bool
     */
    public static function insertSequential(array $sourceArray, $targetKey, int $targetOrder)
    {
        // Append exiting types after position
        if (false === ($indexPosition = array_search($targetKey, array_keys($sourceArray)))) {
            return false;
        }

        // All types that are affected by re-ordering
        $affectedTypes = array_slice($sourceArray, $indexPosition, null, true);

        // Remove the current type (we're going to put it back in later)
        $currentPosition = (int)ArrayHelper::remove($affectedTypes, $targetKey);

        // Already in that position?
        if ($currentPosition === $targetOrder) {
            return true;
        }

        $startingSortOrder = $targetOrder;
        if ($indexPosition++ < $targetOrder) {
            $startingSortOrder = $indexPosition;
        }

        // Prepend current type
        $order = [$targetKey => $targetOrder];

        // Assemble order
        if (false !== ($position = array_search($targetOrder, array_values($affectedTypes)))) {
            $position++;

            $order = array_slice($affectedTypes, 0, $position, true) +
                $order +
                array_slice($affectedTypes, $position, null, true);
        }

        return array_combine(
            range($startingSortOrder, count($order) + 1),
            array_keys($order)
        );
    }
}
