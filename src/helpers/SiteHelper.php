<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use Craft;
use craft\models\Site;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class SiteHelper
{
    /**
     * @param int|string|Site $site
     * @return Site
     */
    public static function get($site = null): Site
    {
        if ($site instanceof Site) {
            return $site;
        }

        if (is_numeric($site)) {
            return Craft::$app->getSites()->getSiteById($site);
        }

        if (is_string($site)) {
            return Craft::$app->getSites()->getSiteByHandle($site);
        }

        return Craft::$app->getSites()->currentSite;
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
