<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\base\Element;
use yii\base\Model;

/**
 * @property int|null $elementId
 * @property Element|null $element
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementRulesTrait
{
    /**
     * @return array
     */
    protected function elementRules(): array
    {
        return [
            [
                [
                    'elementId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'elementId',
                    'element'
                ],
                'safe',
                'on' => [
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
