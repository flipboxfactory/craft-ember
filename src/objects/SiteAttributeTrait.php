<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

/**
 * @property int|null $siteId
 * 
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteAttributeTrait
{
    use SiteMutatorTrait;

    /**
     * @var int|null
     */
    private $siteId;

    /**
     * @inheritDoc
     */
    protected function internalSetSiteId(int $id = null)
    {
        $this->siteId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetSiteId()
    {
        return $this->siteId === null ? null : (int) $this->siteId;
    }
}
