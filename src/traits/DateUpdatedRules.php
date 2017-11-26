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
 * @property DateTime|null $dateUpdated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait DateUpdatedRules
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
