<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\cli;

trait QueueableOptionsTrait
{
    /**
     * @var bool
     * Create and send job to the queue
     * example: -q=1
     */
    public $toQueue = false;

    /**
     * @inheritdoc
     */
    protected function queueOptions()
    {
        return [
            'toQueue'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function queueOptionAliases()
    {
        return [
            'q' => 'to-queue',
        ];
    }
}
