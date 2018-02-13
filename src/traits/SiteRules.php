<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use craft\validators\SiteIdValidator;
use flipbox\ember\helpers\ModelHelper;

/**
 * @property int|null $siteId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait SiteRules
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
                    ModelHelper::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
