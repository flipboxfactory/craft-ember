<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use Craft;
use craft\base\ElementInterface;
use flipbox\craft\ember\actions\CheckAccessTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ElementInterface traitRunInternal(ElementInterface $element)
 */
trait ManageElementTrait
{
    use CheckAccessTrait;

    /**
     * @inheritdoc
     * @param ElementInterface $element
     */
    abstract protected function performAction(ElementInterface $element): bool;

    /**
     * @param ElementInterface $data
     * @return mixed
     * @throws \yii\web\HttpException
     */
    protected function runInternal(ElementInterface $data)
    {
        // Check access
        if (($access = $this->checkAccess($data)) !== true) {
            return $access;
        }

        if (!$this->performAction($data)) {
            return $this->handleFailResponse($data);
        }

        return $this->handleSuccessResponse($data);
    }

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
