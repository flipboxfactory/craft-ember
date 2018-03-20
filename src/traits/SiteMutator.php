<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\models\Site as SiteModel;
use flipbox\ember\helpers\SiteHelper;

/**
 * @property int|null|false $siteId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait SiteMutator
{
    /**
     * @var SiteModel|null
     */
    private $site;

    /**
     * Set associated siteId
     *
     * @param $id
     * @return $this
     */
    public function setSiteId(int $id)
    {
        $this->siteId = $id;
        return $this;
    }

    /**
     * Get associated siteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        if (null === $this->siteId && null !== $this->site) {
            $this->siteId = $this->site->id;
        }

        return $this->siteId !== false ? $this->siteId : null;
    }

    /**
     * Associate a site
     *
     * @param $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = null;

        if (!$site = SiteHelper::resolve($site)) {
            $this->site = false;
            $this->siteId = null;
        } else {
            $this->siteId = $site->id;
            $this->site = $site;
        }

        return $this;
    }

    /**
     * @return SiteModel|null
     */
    public function getSite()
    {
        if ($this->site === null) {
            if (!$site = $this->resolveSite()) {
                $this->siteId = null;
                $this->site = false;
                return null;
            }

            $this->setSite($site);
            return $site;
        }

        $siteId = $this->siteId;
        if ($siteId !== null &&
            $siteId !== $this->site->id
        ) {
            $this->site = null;
            return $this->getSite();
        }

        return !$this->site ? null : $this->site;
    }

    /**
     * @return SiteModel|null
     */
    protected function resolveSite()
    {
        if ($fieldLayoutModel = $this->resolveSiteFromId()) {
            return $fieldLayoutModel;
        }

        return null;
    }

    /**
     * @return SiteModel|null
     */
    private function resolveSiteFromId()
    {
        if (null === $this->siteId) {
            return null;
        }

        return Craft::$app->getSites()->getSiteById($this->siteId);
    }
}
