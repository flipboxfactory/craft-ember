<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\validators;

use craft\validators\ArrayValidator;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class MinMaxValidator extends ArrayValidator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if ($value instanceof QueryInterface) {
            return $this->validateQueryAttribute($model, $attribute, $value);
        }

        return parent::validateAttribute($model, $attribute);
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if ($value instanceof QueryInterface) {
            return $this->validateQueryValue($value);
        }

        return parent::validateValue($value);
    }

    /**
     * @param QueryInterface $query
     * @return array|null the error message and the parameters to be inserted into the error message.
     * Null should be returned if the data is valid.
     */
    protected function validateQueryValue(QueryInterface $query)
    {
        /** @var QueryInterface $value */
        $count = $query->count();

        if ($this->min !== null && $count < $this->min) {
            return [$this->tooFew, ['min' => $this->min]];
        }
        if ($this->max !== null && $count > $this->max) {
            return [$this->tooMany, ['max' => $this->max]];
        }
        if ($this->count !== null && $count !== $this->count) {
            return [$this->notEqual, ['count' => $this->count]];
        }

        return null;
    }

    /**
     * @param $model
     * @param $attribute
     * @param QueryInterface $query
     */
    protected function validateQueryAttribute($model, $attribute, QueryInterface $query)
    {
        $count = $query->count();

        if ($this->min !== null && $count < $this->min) {
            $this->addError($model, $attribute, $this->tooFew, ['min' => $this->min]);
        }
        if ($this->max !== null && $count > $this->max) {
            $this->addError($model, $attribute, $this->tooMany, ['max' => $this->max]);
        }
        if ($this->count !== null && $count !== $this->count) {
            $this->addError($model, $attribute, $this->notEqual, ['count' => $this->count]);
        }
    }
}
