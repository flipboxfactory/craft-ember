<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element\traits;

use Craft;
use craft\base\ElementInterface;

trait Delete
{
    use Manage;

    /**
     * @inheritdoc
     */
    protected function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?: 204;
    }

    /**
     * @inheritdoc
     * @param ElementInterface $element
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->deleteElement($element);
    }
}
