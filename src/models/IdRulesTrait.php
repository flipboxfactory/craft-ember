<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use flipbox\craft\ember\helpers\ModelHelper;

/**
 * @property int|null $id
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait IdRulesTrait
{
    /**
     * @inheritdoc
     */
    protected function idRules()
    {
        return [
            [
                [
                    'id'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'id'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
