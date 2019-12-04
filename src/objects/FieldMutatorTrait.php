<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\base\Field;
use craft\base\FieldInterface;

/**
 * This trait accepts both an Field or and Field Id and ensures that the both
 * the Field and the Id are in sync; If one changes (and does not match the other) it
 * resolves (removes / updates) the other.
 *
 * In addition, this trait is primarily useful when a new Field is set and saved; the Field
 * Id can be retrieved without needing to explicitly set the newly created Id.
 *
 * @property Field|FieldInterface|null $field
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldMutatorTrait
{
    /**
     * @var Field|FieldInterface|null
     */
    private $field;

    /**
     * Internally set the Field Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetFieldId(int $id = null);

    /**
     * Internally get the Field Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetFieldId();

    /**
     * @return bool
     */
    public function isFieldSet(): bool
    {
        return null !== $this->field;
    }

    /**
     * Set associated fieldId
     *
     * @param int|null $id
     * @return $this
     */
    public function setFieldId(int $id = null)
    {
        $this->internalSetFieldId($id);

        if (null !== $this->field && $id != $this->field->id) {
            $this->field = null;
        }

        return $this;
    }

    /**
     * Get associated fieldId
     *
     * @return int|null
     */
    public function getFieldId()
    {
        if (null === $this->internalGetFieldId() && null !== $this->field) {
            $this->setFieldId($this->field->id);
        }

        return $this->internalGetFieldId();
    }

    /**
     * Associate a site
     *
     * @param mixed $field
     * @return $this
     */
    public function setField($field = null)
    {
        $this->field = null;
        $this->internalSetFieldId(null);

        if (null !== ($field = $this->verifyField($field))) {
            $this->field = $field;
            $this->internalSetFieldId($field->id);
        }

        return $this;
    }

    /**
     * @return FieldInterface|Field|null
     */
    public function getField()
    {
        if ($this->field === null) {
            $field = $this->resolveField();
            $this->setField($field);
            return $field;
        }

        $fieldId = $this->internalGetFieldId();
        if ($fieldId !== null && $fieldId != $this->field->id) {
            $this->field = null;
            return $this->getField();
        }

        return $this->field;
    }

    /**
     * @return FieldInterface|Field|null
     */
    protected function resolveField()
    {
        if ($site = $this->resolveFieldFromId()) {
            return $site;
        }

        return null;
    }

    /**
     * @return FieldInterface|Field|null
     */
    private function resolveFieldFromId()
    {
        if (null === ($fieldId = $this->internalGetFieldId())) {
            return null;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Craft::$app->getFields()->getFieldById($fieldId);
    }

    /**
     * @param mixed $field
     * @return FieldInterface|FieldInterface|Field|null
     */
    protected function verifyField($field = null)
    {
        if (null === $field) {
            return null;
        }

        if ($field instanceof FieldInterface) {
            return $field;
        }

        if (is_numeric($field)) {
            return Craft::$app->getFields()->getFieldById($field);
        }

        if (is_string($field)) {
            return Craft::$app->getFields()->getFieldByHandle($field);
        }

        return null;
    }
}
