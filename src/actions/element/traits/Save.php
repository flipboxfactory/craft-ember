<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element\traits;

use Craft;
use craft\base\ElementInterface;
use flipbox\ember\actions\traits\Populate;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method ElementInterface populate(ElementInterface $object);
 */
trait Save
{
    use Populate, Manage;

    /**
     * @param ElementInterface $element
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function runInternal(ElementInterface $element)
    {
        // Check access
        if (($access = $this->checkAccess($element)) !== true) {
            return $access;
        }

        if (!$this->performAction(
            $this->populate($element)
        )) {
            return $this->handleFailResponse($element);
        }

        return $this->handleSuccessResponse($element);
    }

    /**
     * @param ElementInterface $element
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->saveElement($element);
    }
}
