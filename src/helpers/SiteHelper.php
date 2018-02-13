<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use Craft;
use craft\models\Site as SiteModel;
use craft\records\Site as SiteRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class SiteHelper
{
    /**
     * @param null $site
     * @return SiteModel
     */
    public static function get($site = null): SiteModel
    {
        if (null === $site) {
            return Craft::$app->getSites()->currentSite;
        }

        return static::resolve($site);
    }

    /**
     * @param $site
     * @return SiteModel
     */
    public static function resolve($site = null): SiteModel
    {
        if ($site instanceof SiteModel) {
            return $site;
        }

        if (is_numeric($site)) {
            return Craft::$app->getSites()->getSiteById($site);
        }

        if (is_string($site)) {
            return Craft::$app->getSites()->getSiteByHandle($site);
        }

        try {
            $object = Craft::createObject(SiteModel::class, [$site]);
        } catch (\Exception $e) {
            $object = new SiteModel();
            ObjectHelper::populate(
                $object,
                $site
            );
        }

        return $object;
    }

    /**
     * @param int|null $siteId
     * @return int
     */
    public static function resolveSiteId(int $siteId = null): int
    {
        if (is_null($siteId)) {
            $siteId = Craft::$app->getSites()->currentSite->id;
        }

        return $siteId;
    }
}
