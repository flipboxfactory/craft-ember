<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\helpers;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class UrlHelper extends \craft\helpers\UrlHelper
{
    /**
     * @param string $path
     * @param null $params
     * @param string|null $protocol
     * @return string
     * @throws \yii\base\Exception
     */
    public static function siteActionUrl(string $path = '', $params = null, string $protocol = null): string
    {
        $path = Craft::$app->getConfig()->getGeneral()->actionTrigger . '/' . trim($path, '/');

        return static::siteUrl($path, $params, $protocol);
    }

    /**
     * @param string $path
     * @param null $params
     * @param string|null $protocol
     * @return string
     */
    public static function cpActionUrl(string $path = '', $params = null, string $protocol = null): string
    {
        $path = Craft::$app->getConfig()->getGeneral()->actionTrigger . '/' . trim($path, '/');

        return static::cpUrl($path, $params, $protocol);
    }
}
