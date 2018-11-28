<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\base\Field;
use flipbox\craft\ember\helpers\ModelHelper;

/**
 * @property int|null $fieldId
 * @property Field|null $field
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldRulesTrait
{
    /**
     * @return array
     */
    protected function fieldRules(): array
    {
        return [
            [
                [
                    'fieldId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'fieldId',
                    'field'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
