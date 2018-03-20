<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element\traits;

use craft\base\ElementInterface;
use flipbox\ember\actions\traits\CheckAccess;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait View
{
    use CheckAccess;

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
