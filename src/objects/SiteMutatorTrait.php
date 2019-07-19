<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\models\Site;

/**
 * This trait accepts both an Site or and Site Id and ensures that the both
 * the Site and the Id are in sync; If one changes (and does not match the other) it
 * resolves (removes / updates) the other.
 *
 * In addition, this trait is primarily useful when a new Site is set and saved; the Site
 * Id can be retrieved without needing to explicitly set the newly created Id.
 *
 * @property Site|null $site
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteMutatorTrait
{
    /**
     * @var Site|null
     */
    private $site;

    /**
     * Internally set the Site Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetSiteId(int $id = null);

    /**
     * Internally get the Site Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetSiteId();

    /**
     * @return bool
     */
    public function isSiteSet(): bool
    {
        return null !== $this->site;
    }

    /**
     * Set associated siteId
     *
     * @param $id
     * @return $this
     */
    public function setSiteId(int $id)
    {
        $this->internalSetSiteId($id);

        if (null !== $this->site && $id !== $this->site->id) {
            $this->site = null;
        }

        return $this;
    }

    /**
     * Get associated siteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        if (null === $this->internalGetSiteId() && null !== $this->site) {
            $this->setSiteId($this->site->id);
        }

        return $this->internalGetSiteId();
    }

    /**
     * Associate a site
     *
     * @param mixed $site
     * @return $this
     */
    public function setSite($site = null)
    {
        $this->site = null;
        $this->internalSetSiteId(null);

        if (null !== ($site = $this->verifySite($site))) {
            $this->site = $site;
            $this->internalSetSiteId($site->id);
        }

        return $this;
    }

    /**
     * @return Site|null
     */
    public function getSite()
    {
        if ($this->site === null) {
            $site = $this->resolveSite();
            $this->setSite($site);
            return $site;
        }

        $siteId = $this->internalGetSiteId();
        if ($siteId !== null && $siteId !== $this->site->id) {
            $this->site = null;
            return $this->getSite();
        }

        return $this->site;
    }

    /**
     * @return Site|null
     */
    protected function resolveSite()
    {
        if ($site = $this->resolveSiteFromId()) {
            return $site;
        }

        return null;
    }

    /**
     * @return Site|null
     */
    private function resolveSiteFromId()
    {
        if (null === ($siteId = $this->internalGetSiteId())) {
            return null;
        }

        return Craft::$app->getSites()->getSiteById($siteId);
    }

    /**
     * Attempt to verify that the passed 'site' is a valid element.  A primary key or query
     * can be passed to lookup an site.
     *
     * @param mixed $site
     * @return Site|null
     */
    protected function verifySite($site = null)
    {
        if (null === $site) {
            return null;
        }

        if ($site instanceof Site) {
            return $site;
        }

        if (is_numeric($site)) {
            return Craft::$app->getSites()->getSiteById($site);
        }

        if (is_string($site)) {
            return Craft::$app->getSites()->getSiteByHandle($site);
        }

        return null;
    }
}
