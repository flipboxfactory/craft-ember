<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\models\FieldLayout as FieldLayoutModel;
use yii\base\Model;

/**
 * @property int|null $fieldLayoutId
 * @property FieldLayoutModel|null $fieldLayout
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldLayoutRulesTrait
{
    /**
     * @return array
     */
    protected function fieldLayoutRules(): array
    {
        return [
            [
                [
                    'fieldLayoutId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'fieldLayoutId',
                    'fieldLayout'
                ],
                'safe',
                'on' => [
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
