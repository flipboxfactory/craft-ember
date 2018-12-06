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
 *
 * @property int $statusCodeNotFound
 * @property int $messageNotFound
 */
trait LookupTrait
{
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

    /**
     * HTTP not found response code
     *
     * @return int
     */
    protected function statusCodeNotFound(): int
    {
        return $this->statusCodeNotFound ?? 404;
    }

    /**
     * @return string
     */
    protected function messageNotFound(): string
    {
        return $this->messageNotFound ?? 'Unable to find object.';
    }

    /**
     * @return null
     * @throws HttpException
     */
    protected function handleNotFoundResponse()
    {
        throw new HttpException(
            $this->statusCodeNotFound(),
            $this->messageNotFound()
        );
    }
}
