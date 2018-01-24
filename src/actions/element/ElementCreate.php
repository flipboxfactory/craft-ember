<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element;

use craft\base\ElementInterface;
use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ElementCreate extends Action
{
    use traits\Save;

    /**
     * @param array $config
     * @return ElementInterface
     */
    abstract protected function newElement(array $config = []): ElementInterface;

    /**
     * @inheritdoc
     * @return ElementInterface
     */
    public function run()
    {
        return $this->runInternal($this->newElement());
    }

    /**
     * @inheritdoc
     */
    public function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?: 201;
    }
}
