<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

/**
 * @property int|null $elementId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementAttributeTrait
{
    use ElementMutatorTrait;

    /**
     * @var int|null
     */
    private $elementId;

    /**
     * @inheritDoc
     */
    protected function internalSetElementId(int $id = null)
    {
        $this->elementId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetElementId()
    {
        return $this->elementId === null ? null : (int)$this->elementId;
    }
}
