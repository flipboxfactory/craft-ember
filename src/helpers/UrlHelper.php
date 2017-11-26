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
class UrlHelper extends \craft\helpers\UrlHelper
{
    /**
     * @param string $path
     * @param array|string|null $params
     * @param string|null $protocol The protocol to use (e.g. http, https). If empty, the protocol used for the
     * current request will be used.
     *
     * @return string
     */
    public static function siteActionUrl(string $path = '', $params = null, string $protocol = null): string
    {
        $path = Craft::$app->getConfig()->getGeneral()->actionTrigger . '/' . trim($path, '/');

        return static::siteUrl($path, $params, $protocol);
    }

    /**
     * @param string $path
     * @param array|string|null $params
     * @param string|null $protocol The protocol to use (e.g. http, https). If empty, the protocol used for the
     * current request will be used.
     *
     * @return string
     */
    public static function cpActionUrl(string $path = '', $params = null, string $protocol = null): string
    {
        $path = Craft::$app->getConfig()->getGeneral()->actionTrigger . '/' . trim($path, '/');

        return static::cpUrl($path, $params, $protocol);
    }
}
