<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\cli;

trait DebugOptionsTrait
{
    /**
     * @var bool
     * Prints verbose output
     * example: -d=1
     */
    public $debug = false;

    /**
     * @inheritdoc
     */
    protected function debugOptions()
    {
        return [
            'debug'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function debugOptionAliases()
    {
        return [
            'd' => 'debug',
        ];
    }
}
