<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use yii\web\HttpException;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LookupTrait
{
    use NotFoundTrait;

    /**
     * @param mixed $object
     * @return mixed|Response
     */
    abstract protected function runInternal($object);

    /**
     * @param string|int $identifier
     * @return mixed|null
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
