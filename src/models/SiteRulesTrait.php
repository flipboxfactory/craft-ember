<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use craft\models\Site;
use craft\validators\SiteIdValidator;
use yii\base\Model;

/**
 * @property int|null $siteId
 * @property Site|null $site
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteRulesTrait
{
    /**
     * @return array
     */
    protected function siteRules(): array
    {
        return [
            [
                [
                    'siteId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'siteId'
                ],
                SiteIdValidator::class
            ],
            [
                [
                    'siteId',
                    'site'
                ],
                'safe',
                'on' => [
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
