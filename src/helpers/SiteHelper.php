<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\helpers;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class SiteHelper
{
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
