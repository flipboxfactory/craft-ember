<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use craft\elements\User as UserElement;
use flipbox\ember\helpers\ModelHelper;

/**
 * @property int|null $userId
 * @property UserElement|null $user
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait UserRules
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
