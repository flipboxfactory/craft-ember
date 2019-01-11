<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\elements;

use craft\errors\ElementNotFoundException;
use craft\helpers\Json;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.1.0
 */
trait ExplicitElementTrait
{
    /**
     * Returns a single element instance by a primary key or a set of element criteria parameters.
     *
     * The method accepts:
     *
     *  - an int: query by a single ID value and return the corresponding element (or null if not found).
     *  - an array of name-value pairs: query by a set of parameter values and return the first element
     *    matching all of them (or null if not found).
     *
     * Note that this method will automatically call the `one()` method and return an
     * [[ElementInterface|\craft\base\Element]] instance. For example,
     *
     * ```php
     * // find a single entry whose ID is 10
     * $entry = Entry::findOne(10);
     * // the above code is equivalent to:
     * $entry = Entry::find->id(10)->one();
     * // find the first user whose email ends in "example.com"
     * $user = User::findOne(['email' => '*example.com']);
     * // the above code is equivalent to:
     * $user = User::find()->email('*example.com')->one();
     * ```
     *
     * @param mixed $criteria The element ID or a set of element criteria parameters
     * @return static|null Element instance matching the condition, or null if nothing matches.
     */
    abstract public static function findOne($criteria = null);

    /**
     * Returns a list of elements that match the specified ID(s) or a set of element criteria parameters.
     *
     * The method accepts:
     *
     *  - an int: query by a single ID value and return an array containing the corresponding element
     *    (or an empty array if not found).
     *  - an array of integers: query by a list of ID values and return the corresponding elements (or an
     *    empty array if none was found).
     *    Note that an empty array will result in an empty result as it will be interpreted as a search for
     *    primary keys and not an empty set of element criteria parameters.
     *  - an array of name-value pairs: query by a set of parameter values and return an array of elements
     *    matching all of them (or an empty array if none was found).
     *
     * Note that this method will automatically call the `all()` method and return an array of
     * [[ElementInterface|\craft\base\Element]] instances. For example,
     *
     * ```php
     * // find the entries whose ID is 10
     * $entries = Entry::findAll(10);
     * // the above code is equivalent to:
     * $entries = Entry::find()->id(10)->all();
     * // find the entries whose ID is 10, 11 or 12.
     * $entries = Entry::findAll([10, 11, 12]);
     * // the above code is equivalent to:
     * $entries = Entry::find()->id([10, 11, 12]])->all();
     * // find users whose email ends in "example.com"
     * $users = User::findAll(['email' => '*example.com']);
     * // the above code is equivalent to:
     * $users = User::find()->email('*example.com')->all();
     * ```
     *
     * @param mixed $criteria The element ID, an array of IDs, or a set of element criteria parameters
     * @return static[] an array of Element instances, or an empty array if nothing matches.
     */
    abstract public static function findAll($criteria = null): array;

    /**
     * Returns a single element instance by a primary key or a set of element criteria parameters.
     *
     * The method accepts:
     *
     *  - an int: query by a single ID value and return the corresponding element (or null if not found).
     *  - an array of name-value pairs: query by a set of parameter values and return the first element
     *    matching all of them (or null if not found).
     *
     * Note that this method will automatically call the `one()` method and return an
     * [[ElementInterface|\craft\base\Element]] instance. For example,
     *
     * ```php
     * // find a single entry whose ID is 10
     * $entry = Entry::findOne(10);
     * // the above code is equivalent to:
     * $entry = Entry::find->id(10)->one();
     * // find the first user whose email ends in "example.com"
     * $user = User::findOne(['email' => '*example.com']);
     * // the above code is equivalent to:
     * $user = User::find()->email('*example.com')->one();
     * ```
     *
     * @param mixed $criteria The element ID or a set of element criteria parameters
     * @return static Element instance matching the condition, or null if nothing matches.
     * @throws ElementNotFoundException
     */
    public static function getOne($criteria)
    {
        if (null === ($element = static::findOne($criteria))) {
            throw new ElementNotFoundException(
                sprintf(
                    "Organization not found with the following criteria: %s",
                    Json::encode($criteria)
                )
            );
        }

        return $element;
    }

    /**
     * Returns a list of elements that match the specified ID(s) or a set of element criteria parameters.
     *
     * The method accepts:
     *
     *  - an int: query by a single ID value and return an array containing the corresponding element
     *    (or an empty array if not found).
     *  - an array of integers: query by a list of ID values and return the corresponding elements (or an
     *    empty array if none was found).
     *    Note that an empty array will result in an empty result as it will be interpreted as a search for
     *    primary keys and not an empty set of element criteria parameters.
     *  - an array of name-value pairs: query by a set of parameter values and return an array of elements
     *    matching all of them (or an empty array if none was found).
     *
     * Note that this method will automatically call the `all()` method and return an array of
     * [[ElementInterface|\craft\base\Element]] instances. For example,
     *
     * ```php
     * // find the entries whose ID is 10
     * $entries = Entry::findAll(10);
     * // the above code is equivalent to:
     * $entries = Entry::find()->id(10)->all();
     * // find the entries whose ID is 10, 11 or 12.
     * $entries = Entry::findAll([10, 11, 12]);
     * // the above code is equivalent to:
     * $entries = Entry::find()->id([10, 11, 12]])->all();
     * // find users whose email ends in "example.com"
     * $users = User::findAll(['email' => '*example.com']);
     * // the above code is equivalent to:
     * $users = User::find()->email('*example.com')->all();
     * ```
     *
     * @param mixed $criteria The element ID, an array of IDs, or a set of element criteria parameters
     * @return static[] an array of Element instances, or an empty array if nothing matches.
     * @throws ElementNotFoundException
     */
    public static function getAll($criteria)
    {
        $elements = static::findAll($criteria);

        if (empty($elements)) {
            throw new ElementNotFoundException(
                sprintf(
                    "Organization not found with the following criteria: %s",
                    Json::encode($criteria)
                )
            );
        }

        return $elements;
    }
}
