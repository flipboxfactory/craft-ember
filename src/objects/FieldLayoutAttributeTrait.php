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
trait FieldLayoutAttributeTrait
{
    use FieldLayoutMutatorTrait;

    /**
     * @var int|null
     */
    private $fieldLayoutId;

    /**
     * @inheritDoc
     */
    protected function internalSetFieldLayoutId(int $id = null)
    {
        $this->fieldLayoutId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetFieldLayoutId()
    {
        return $this->fieldLayoutId === null ? null : (int) $this->fieldLayoutId;
    }
}
