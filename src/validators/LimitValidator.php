<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\validators;

use yii\base\Model;
use yii\db\Query;
use yii\validators\Validator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class LimitValidator extends Validator
{
    /**
     * @var callable This can be a global function name, anonymous function, etc.
     * The function signature must be as follows,
     *
     * ```php
     * function foo(\yii\db\Query $query) {
     *     // modify $query here
     *     return $query;
     * }
     * ```
     */
    public $query;

    /**
     * @var callable This can be a global function name, anonymous function, etc.
     * The function signature must be as follows,
     *
     * ```php
     * function foo(\yii\base\Model $model) {
     *     // compute $limit here
     *     return (int) $limit;
     * }
     * ```
     */
    public $limit;

    /**
     * @inheritdoc
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($model, $attribute)
    {
        if (0 === ($limit = $this->getLimit($model))) {
            return; // no limit
        }

        $count = (int)$this->getQuery($model)->count();

        if ($count >= $limit) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /**
     * @param Model $model
     * @return int
     */
    protected function getLimit(Model $model): int
    {
        return (int)call_user_func($this->limit, $model);
    }

    /**
     * @param Model $model
     * @return Query
     */
    protected function getQuery(Model $model): Query
    {
        return call_user_func($this->query, $model);
    }
}
