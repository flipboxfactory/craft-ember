<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\validators;

use craft\helpers\ArrayHelper;
use yii\base\Model;
use yii\validators\Validator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class ModelValidator extends Validator
{
    /**
     * @var array list of attribute names that should be validated.
     * If this parameter is empty, it means any attribute listed in the applicable
     * validation rules should be validated.
     */
    public $modelAttributeNames = null;

    /**
     * @var bool whether to call [[clearErrors()]] before performing validation
     */
    public $clearErrors = true;

    /**
     * @var string the scenario used to validate the model.
     * Defaults to null, meaning no limit.
     * @see tooSmall for the customized message for a file that is too small.
     */
    public $scenario;

    /**
     * Validates a value.
     * A validator class can implement this method to support data validation out of the context of a data model.
     * @param mixed $value the data value to be validated.
     * @return array|null the error message and the parameters to be inserted into the error message.
     * Null should be returned if the data is valid.
     */
    protected function validateValue($value)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Model) {
            $value = [$value];
        }

        if (!is_array($value) ||
            ArrayHelper::isAssociative($value) ||
            false === $this->validateModels($value)
        ) {
            return [$this->message, []];
        }

        return null;
    }

    /**
     * @param array $models
     * @return bool
     */
    protected function validateModels(array $models): bool
    {
        $isValid = true;

        foreach ($models as $model) {
            if (false === $this->validateModel($model)) {
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * @param Model $model
     * @return bool
     */
    protected function validateModel(Model $model): bool
    {
        // Current scenario
        $defaultScenarios = $model->getScenario();

        // Change to validation scenario
        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }

        // Validate
        $isValid = true;
        if (!$model->validate($this->modelAttributeNames, $this->clearErrors)) {
            $isValid = false;
        }

        // Revert back to prior scenario
        if ($this->scenario) {
            $model->setScenario($defaultScenarios);
        }

        return $isValid;
    }
}
