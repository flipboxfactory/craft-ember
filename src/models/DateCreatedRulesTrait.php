<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\validators\DateTimeValidator;
use DateTime;
use yii\base\Model;

/**
 * @property DateTime|null $dateCreated
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait DateCreatedRulesTrait
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
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
