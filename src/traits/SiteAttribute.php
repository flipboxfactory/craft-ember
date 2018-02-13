<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait SiteAttribute
{
    use SiteRules, SiteMutator;

    /**
     * @var int|null
     */
    private $siteId;

    /**
     * @return array
     */
    protected function siteFields(): array
    {
        return [
            'siteId'
        ];
    }

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
            'siteId' => Craft::t('organization', 'Site Id')
        ];
    }
}
