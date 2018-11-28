<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use craft\base\ElementInterface;
use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class CreateElement extends Action
{
    use SaveElementTrait;

    /**
     * @inheritdoc
     * @return ElementInterface
     */
    abstract protected function newElement(array $config = []): ElementInterface;

    /**
     * @inheritdoc
     * @return ElementInterface
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     * @throws \yii\web\UnauthorizedHttpException
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
