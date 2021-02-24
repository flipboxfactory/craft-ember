<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\models;

use flipbox\craft\ember\actions\NotFoundTrait;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LookupModelTrait
{
    use NotFoundTrait;

    /**
     * @inheritdoc
     * @param Model $model
     * @return Model|Response
     */
    abstract protected function runInternal(Model $model);

    /**
     * @param string|int $identifier
     * @return Model|null
     */
    abstract protected function find($identifier);

    /**
     * @param $identifier
     * @return mixed|null|Response
     * @throws HttpException
     */
    public function run($identifier)
    {
        if (!$object = $this->find($identifier)) {
            return $this->handleNotFoundResponse();
        }

        return $this->runInternal($object);
    }
}
