<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\models\FieldLayout;
use craft\models\FieldLayout as FieldLayoutModel;
use flipbox\ember\helpers\FieldLayoutHelper;

/**
 * @property int|null $fieldLayoutId
 * @property FieldLayoutModel|null $fieldLayout
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FieldLayoutMutator
{
    /**
     * @var FieldLayoutModel|null
     */
    private $fieldLayout;

    /**
     * @return string
     */
    abstract static protected function fieldLayoutType(): string;

    /**
     * Set associated fieldLayoutId
     *
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
     * Associate a fieldLayout
     *
     * @param $fieldLayout
     * @return $this
     */
    public function setFieldLayout($fieldLayout)
    {
        $this->fieldLayout = null;

        if (!$fieldLayout = FieldLayoutHelper::resolve($fieldLayout)) {
            $this->fieldLayout = $this->fieldLayoutId = null;
        } else {
            $this->fieldLayoutId = $fieldLayout->id;
            $this->fieldLayout = $fieldLayout;
        }

        return $this;
    }

    /**
     * @return FieldLayoutModel|null
     */
    public function getFieldLayout(): FieldLayoutModel
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
     * @param FieldLayoutModel $fieldLayout
     * @return FieldLayoutModel
     */
    protected function setTypeOnFieldLayout(FieldLayoutModel $fieldLayout): FieldLayoutModel
    {
        if ($fieldLayout->type === null) {
            $fieldLayout->type = static::fieldLayoutType();
        }

        return $fieldLayout;
    }

    /**
     * @return FieldLayoutModel|null
     */
    protected function resolveFieldLayout()
    {
        if ($fieldLayoutModel = $this->resolveFieldLayoutFromId()) {
            return $fieldLayoutModel;
        }

        return $this->resolveFieldLayoutFromType();
    }

    /**
     * @return FieldLayoutModel|null
     */
    private function resolveFieldLayoutFromId()
    {
        if (null === $this->fieldLayoutId) {
            return null;
        }

        return Craft::$app->getFields()->getLayoutById($this->fieldLayoutId);
    }

    /**
     * @return FieldLayoutModel|null
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
}
