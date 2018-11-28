<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\elements\User as UserElement;
use flipbox\craft\ember\helpers\ModelHelper;

/**
 * @property int|null $userId
 * @property UserElement|null $user
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UserRulesTrait
{
    /**
     * @return array
     */
    protected function userRules(): array
    {
        return [
            [
                [
                    'userId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'userId',
                    'user'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
