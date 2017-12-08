<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\model;

use yii\base\Action;
use yii\base\Model;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ModelCreate extends Action
{
    use traits\Save;

    /**
     * @param array $config
     * @return Model
     */
    abstract protected function newModel(array $config = []): Model;

    /**
     * @return Model|null|Response
     */
    public function run()
    {
        return $this->runInternal($this->newModel());
    }

    /**
     * @inheritdoc
     */
    public function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?: 201;
    }
}
