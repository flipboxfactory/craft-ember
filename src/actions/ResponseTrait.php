<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use Craft;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.6.5
 *
 * @property int $statusCodeSuccess
 * @property int $statusCodeFail
 */
trait ResponseTrait
{
    /**
     * HTTP success response code
     *
     * @return int
     */
    protected function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?? 200;
    }

    /**
     * HTTP fail response code
     *
     * @return int
     */
    protected function statusCodeFail(): int
    {
        return $this->statusCodeFail ?? 400;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function handleSuccessResponse($data)
    {
        // Success status code
        Craft::$app->getResponse()->setStatusCode($this->statusCodeSuccess());
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function handleFailResponse($data)
    {
        Craft::$app->getResponse()->setStatusCode($this->statusCodeFail());
        return $data;
    }
}
