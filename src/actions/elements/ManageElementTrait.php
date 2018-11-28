<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use craft\base\ElementInterface;
use flipbox\craft\ember\actions\ManageTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ElementInterface traitRunInternal(ElementInterface $element)
 */
trait ManageElementTrait
{
    use ManageTrait {
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
     * @return ElementInterface
     */
    protected function runInternal(ElementInterface $element)
    {
        return $this->traitRunInternal($element);
    }
}
