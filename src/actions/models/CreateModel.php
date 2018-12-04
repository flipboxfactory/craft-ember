<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use yii\base\Action;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class CreateModel extends Action
{
    use SaveModelTrait;

    /**
     * @var array
     */
    public $validBodyParams = [];

    /**
     * @param array $config
     * @return Model
     */
    abstract protected function newModel(array $config = []): Model;

    /**
     * @return Model|null
     * @throws \yii\web\UnauthorizedHttpException
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

    /**
     * Body params that should be set on the record.
     *
     * @return array
     */
    protected function validBodyParams(): array
    {
        return $this->validBodyParams;
    }
}
