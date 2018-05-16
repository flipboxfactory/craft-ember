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
     * @inheritdoc
     * @param ElementInterface $element
     */
    public function runInternal(ElementInterface $element)
    {
        // Populate
        $this->populate($element);

        // Check access
        if (($access = $this->checkAccess($element)) !== true) {
            return $access;
        }

        if (!$this->performAction($element)) {
            return $this->handleFailResponse($element);
        }

        return $this->handleSuccessResponse($element);
    }

    /**
     * @inheritdoc
     * @param ElementInterface $element
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->saveElement($element);
    }
}
