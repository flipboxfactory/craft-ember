<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

/**
 * @property int|null $fieldId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldAttributeTrait
{
    use FieldMutatorTrait;

    /**
     * @var int|null
     */
    private $fieldId;

    /**
     * @inheritDoc
     */
    protected function internalSetFieldId(int $id = null)
    {
        $this->fieldId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetFieldId()
    {
        return $this->fieldId === null ? null : (int)$this->fieldId;
    }
}
