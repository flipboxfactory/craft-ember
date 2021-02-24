<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use yii\web\HttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.6.5
 *
 * @property int|null $statusCodeNotFound
 * @property int|null $messageNotFound
 */
trait NotFoundTrait
{
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
