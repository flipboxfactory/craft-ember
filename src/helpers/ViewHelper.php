<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use flipbox\ember\views\ViewInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ViewHelper
{
    /**
     * @param $view
     * @return bool
     */
    public static function isView($view)
    {
        return $view instanceof ViewInterface;
    }

    /**
     * @param $view
     * @return bool
     */
    public static function isViewClass($view)
    {
        return is_string($view) && (is_subclass_of($view, ViewInterface::class) || $view instanceof ViewInterface);
    }
}
