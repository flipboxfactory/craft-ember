<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use Craft;
use craft\base\ElementInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property int $statusCodeSuccess
 */
trait DeleteElementTrait
{
    use ManageElementTrait;

    /**
     * @inheritdoc
     */
    protected function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?? 204;
    }

    /**
     * @inheritdoc
     * @param ElementInterface $element
     * @throws \Throwable
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->deleteElement($element);
    }
}
