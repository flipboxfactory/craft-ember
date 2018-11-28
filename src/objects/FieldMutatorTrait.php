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
 * @property int|null $fieldId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldMutatorTrait
{
    /**
     * @var Field|null
     */
    private $field;

    /**
     * Set associated fieldId
     *
     * @param int|null $id
     * @return $this
     */
    public function setFieldId(int $id = null)
    {
        $this->fieldId = $id;
        return $this;
    }

    /**
     * Get associated fieldId
     *
     * @return int|null
     */
    public function getFieldId()
    {
        if (null === $this->fieldId && null !== $this->field) {
            $this->fieldId = $this->field->id;
        }

        return $this->fieldId;
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

        if (!$field = $this->internalResolveField($field)) {
            $this->field = $this->fieldId = null;
        } else {
            $this->fieldId = $field->id;
            $this->field = $field;
        }

        return $this;
    }

    /**
     * @return Field|null
     */
    public function getField()
    {
        if ($this->field === null) {
            $site = $this->resolveField();
            $this->setField($site);
            return $site;
        }

        $fieldId = $this->fieldId;
        if ($fieldId !== null &&
            $fieldId !== $this->field->id
        ) {
            $this->field = null;
            return $this->getField();
        }

        return $this->field;
    }

    /**
     * @return Field|null
     */
    protected function resolveField()
    {
        if ($site = $this->resolveFieldFromId()) {
            return $site;
        }

        return null;
    }

    /**
     * @return Field|null
     */
    private function resolveFieldFromId()
    {
        if (null === $this->fieldId) {
            return null;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Craft::$app->getFields()->getFieldById($this->fieldId);
    }

    /**
     * @param mixed $field
     * @return FieldInterface|Field|null
     */
    protected function internalResolveField($field = null)
    {
        if ($field instanceof FieldInterface) {
            return $field;
        }

        if (is_numeric($field)) {
            return Craft::$app->getFields()->getFieldById($field);
        }

        if (is_string($field)) {
            return Craft::$app->getFields()->getFieldByHandle($field);
        }

        return Craft::$app->getFields()->createField($field);
    }
}
