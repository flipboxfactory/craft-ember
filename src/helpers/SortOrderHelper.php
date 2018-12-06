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
class SortOrderHelper
{
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
        $altered = self::ensureSequential($sourceArray);

        // Must be greater than 1
        $targetOrder = $targetOrder > 0 ? $targetOrder : 1;

        // Append exiting types after position
        if (false === ($indexPosition = array_search($targetKey, array_keys($sourceArray)))) {
            return false;
        }

        $affectedIndex = 0;
        $affectedTypes = $sourceArray;
        if ($altered === false) {
            // Determine the furthest affected index
            $affectedIndex = $indexPosition >= $targetOrder ? ($targetOrder - 1) : $indexPosition;

            // All that are affected by re-ordering
            $affectedTypes = array_slice($sourceArray, $affectedIndex, null, true);
        }

        // Remove the current (we're going to put it back in later)
        $currentPosition = (int)ArrayHelper::remove($affectedTypes, $targetKey);

        // Prepend current type
        $order = [$targetKey => $targetOrder];

        // Already in that position?
        if ($altered === false && $currentPosition === $targetOrder) {
            return true;
        }

        $startingSortOrder = $targetOrder;
        if ($affectedIndex++ < $targetOrder) {
            $startingSortOrder = $affectedIndex;
        }

        // Assemble order
        if (false !== ($position = array_search($targetOrder, array_values($affectedTypes)))) {
            if ($indexPosition < $targetOrder) {
                $position++;
            }
        } else {
            // Couldn't find a matching position (likely this means it was in the correct position already)

            if ($indexPosition < $targetOrder) {
                $position = $indexPosition;
            }

            // The target order is way beyond our max
            if ($targetOrder > count($affectedTypes)) {
                $position = count($affectedTypes);
            }
        }

        if ($position > 0) {
            $order = array_slice($affectedTypes, 0, $position, true) + $order;
        }

        $order += array_slice($affectedTypes, $position, null, true);

        return array_flip(array_combine(
            range($startingSortOrder, count($order)),
            array_keys($order)
        ));
    }

    /**
     *
     * @param array $sourceArray
     * @return bool
     */
    private static function ensureSequential(array &$sourceArray): bool
    {
        $ct = 0;
        $altered = false;
        foreach ($sourceArray as $key => &$sortOrder) {
            $ct++;
            $sortOrder = (int)$sortOrder;

            if ($sortOrder !== $ct) {
                $altered = true;
                $sortOrder = $ct;
            }
        }

        return $altered;
    }
}
