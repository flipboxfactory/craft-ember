<?php

namespace flipbox\ember\actions\element\traits;

use Craft;
use craft\base\ElementInterface;

trait Save
{
    /**
     * @param ElementInterface $element
     * @return bool
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->saveElement($element);
    }
}
