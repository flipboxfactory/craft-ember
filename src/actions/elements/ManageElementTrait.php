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
use flipbox\craft\ember\actions\ResponseTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ElementInterface traitRunInternal(ElementInterface $element)
 */
trait ManageElementTrait
{
    use CheckAccessTrait, ResponseTrait;

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
}
