<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\traits;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait PrepareData
{
    /**
     * @var null|callable
     */
    public $prepareData = null;

    /**
     * @param $data
     * return $void
     */
    protected function prepareData(&...$data)
    {
        if ($this->prepareData) {
            call_user_func_array($this->prepareData, $data);
        }
    }
}
