<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use craft\base\ElementInterface;
use flipbox\craft\ember\actions\CheckAccessTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ViewElementTrait
{
    use CheckAccessTrait;

    /**
     * @param ElementInterface $element
     * @return ElementInterface
     * @throws \yii\web\UnauthorizedHttpException
     */
    protected function runInternal(ElementInterface $element)
    {
        // Check access
        if (($access = $this->checkAccess($element)) !== true) {
            return $access;
        }

        return $element;
    }
}
