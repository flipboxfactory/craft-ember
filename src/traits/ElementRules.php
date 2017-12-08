<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use craft\base\Element;
use flipbox\ember\helpers\ModelHelper;

/**
 * @property int|null $elementId
 * @property Element|null $element
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementRules
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
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
