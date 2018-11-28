<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\models\Site as SiteModel;
use flipbox\craft\ember\helpers\SiteHelper;

/**
 * @property int|null $siteId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait SiteMutatorTrait
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

        return $this->siteId;
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

        if (($site = SiteHelper::resolve($site)) === null) {
            $this->site = $this->siteId = null;
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
            $site = $this->resolveSite();
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

        return $this->site;
    }

    /**
     * @return SiteModel|null
     */
    protected function resolveSite()
    {
        if ($site = $this->resolveSiteFromId()) {
            return $site;
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
