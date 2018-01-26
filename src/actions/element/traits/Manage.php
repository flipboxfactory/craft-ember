<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\element\traits;

use craft\base\ElementInterface;
use flipbox\ember\actions\traits\Manage as BaseManage;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Manage
{
    use BaseManage {
        runInternal as traitRunInternal;
    }

    /**
     * @inheritdoc
     * @param ElementInterface $element
     */
    abstract protected function performAction(ElementInterface $element): bool;

    /**
     * @inheritdoc
     * @param ElementInterface $element
     * @return Model
     */
    protected function runInternal(ElementInterface $element)
    {
        return $this->traitRunInternal($element);
    }
}
