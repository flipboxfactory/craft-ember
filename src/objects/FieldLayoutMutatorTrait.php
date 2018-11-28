<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\models\FieldLayout;
use flipbox\craft\ember\helpers\ObjectHelper;

/**
 * @property int|null $fieldLayoutId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait FieldLayoutMutatorTrait
{
    /**
     * @var FieldLayout|null
     */
    private $fieldLayout;

    /**
     * @return string
     */
    abstract static protected function fieldLayoutType(): string;

    /**
     * @param $id
     * @return $this
     */
    public function setFieldLayoutId(int $id)
    {
        $this->fieldLayoutId = $id;
        return $this;
    }

    /**
     * Get associated fieldLayoutId
     *
     * @return int|null
     */
    public function getFieldLayoutId()
    {
        if (null === $this->fieldLayoutId && null !== $this->fieldLayout) {
            $this->fieldLayoutId = $this->fieldLayout->id;
        }

        return $this->fieldLayoutId;
    }

    /**
     * @param mixed $fieldLayout
     * @return $this
     */
    public function setFieldLayout($fieldLayout = null)
    {
        $this->fieldLayout = null;

        if (!$fieldLayout = $this->internalResolveFieldLayout($fieldLayout)) {
            $this->fieldLayout = $this->fieldLayoutId = null;
        } else {
            $this->fieldLayoutId = $fieldLayout->id;
            $this->fieldLayout = $fieldLayout;
        }

        return $this;
    }

    /**
     * @return FieldLayout
     */
    public function getFieldLayout(): FieldLayout
    {
        if ($this->fieldLayout === null) {
            if (!$fieldLayout = $this->resolveFieldLayout()) {
                $fieldLayout = $this->createFieldLayout();
            }

            $this->setFieldLayout($fieldLayout);
            return $this->setTypeOnFieldLayout($fieldLayout);
        }

        $fieldLayoutId = $this->fieldLayoutId;
        if ($fieldLayoutId !== null &&
            $fieldLayoutId !== $this->fieldLayout->id
        ) {
            $this->fieldLayout = null;
            return $this->getFieldLayout();
        }

        return $this->setTypeOnFieldLayout($this->fieldLayout);
    }

    /**
     * @param FieldLayout $fieldLayout
     * @return FieldLayout
     */
    protected function setTypeOnFieldLayout(FieldLayout $fieldLayout): FieldLayout
    {
        if ($fieldLayout->type === null) {
            $fieldLayout->type = static::fieldLayoutType();
        }

        return $fieldLayout;
    }

    /**
     * @return FieldLayout|null
     */
    protected function resolveFieldLayout()
    {
        if ($fieldLayoutModel = $this->resolveFieldLayoutFromId()) {
            return $fieldLayoutModel;
        }

        return $this->resolveFieldLayoutFromType();
    }

    /**
     * @return FieldLayout|null
     */
    private function resolveFieldLayoutFromId()
    {
        if (null === $this->fieldLayoutId) {
            return null;
        }

        return Craft::$app->getFields()->getLayoutById($this->fieldLayoutId);
    }

    /**
     * @return FieldLayout|null
     */
    private function resolveFieldLayoutFromType()
    {
        return Craft::$app->getFields()->getLayoutByType(
            static::fieldLayoutType()
        );
    }

    /**
     * @return FieldLayout
     */
    private function createFieldLayout(): FieldLayout
    {
        $fieldLayoutModel = new FieldLayout([
            'type' => $this->fieldLayoutType()
        ]);

        return $fieldLayoutModel;
    }

    /**
     * @param $fieldLayout
     * @return FieldLayout|null
     */
    protected function internalResolveFieldLayout($fieldLayout = null): FieldLayout
    {
        if ($fieldLayout instanceof FieldLayout) {
            return $fieldLayout;
        }

        if (is_numeric($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutById($fieldLayout);
        }

        if (is_string($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutByType($fieldLayout);
        }

        try {
            $object = Craft::createObject(FieldLayout::class, [$fieldLayout]);
        } catch (\Exception $e) {
            $object = new FieldLayout();
            ObjectHelper::populate(
                $object,
                $fieldLayout
            );
        }

        /** @var FieldLayout $object */
        return $object;
    }
}