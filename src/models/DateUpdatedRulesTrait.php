<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\validators\DateTimeValidator;
use DateTime;
use flipbox\craft\ember\helpers\ModelHelper;

/**
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateUpdatedRulesTrait
{
    /**
     * @inheritdoc
     */
    public function dateUpdatedRules()
    {
        return [
            [
                [
                    'dateUpdated'
                ],
                DateTimeValidator::class
            ],
            [
                [
                    'dateUpdated'
                ],
                'safe',
                'on' => [
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
