<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use yii\base\Model;

/**
 * @property string|null $uid
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait UidRulesTrait
{
    /**
     * @inheritdoc
     */
    public function uidRules()
    {
        return [
            [
                [
                    'uid'
                ],
                'safe',
                'on' => [
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
