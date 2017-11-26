<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use craft\validators\DateTimeValidator;
use DateTime;
use flipbox\ember\helpers\ModelHelper;

/**
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateCreatedRules
{
    /**
     * @inheritdoc
     */
    public function dateCreatedRules()
    {
        return [
            [
                [
                    'dateCreated'
                ],
                DateTimeValidator::class
            ],
            [
                [
                    'dateCreated'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
