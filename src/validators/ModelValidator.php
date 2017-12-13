<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\validators;

use yii\base\Model;
use yii\validators\Validator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
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
        if (!$value instanceof Model) {
            return null;
        }

        // Current scenario
        $defaultScenarios = $value->getScenario();

        // Change to validation scenario
        if ($this->scenario) {
            $value->setScenario($this->scenario);
        }

        // Validate
        $errors = null;
        if (!$value->validate($this->modelAttributeNames, $this->clearErrors)) {
            $errors = [$this->message, []];
        }

        // Revert back to prior scenario
        if ($this->scenario) {
            $value->setScenario($defaultScenarios);
        }

        return $errors;
    }
}
