<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\models;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteAttributeTrait
{
    use SiteRulesTrait, \flipbox\craft\ember\objects\SiteAttributeTrait;

    /**
     * @return array
     */
    protected function siteAttributes(): array
    {
        return [
            'siteId'
        ];
    }

    /**
     * @return array
     */
    protected function siteAttributeLabels(): array
    {
        return [
            'siteId' => Craft::t('app', 'Site Id')
        ];
    }
}
