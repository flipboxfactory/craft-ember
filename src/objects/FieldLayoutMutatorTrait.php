<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\models\FieldLayout;

/**
 * This trait accepts both an FieldLayout or and FieldLayout Id and ensures that the both
 * the FieldLayout and the Id are in sync. If one changes (and does not match the other) it
 * resolves (removes / updates) the other.
 *
 * In addition, this trait is primarily useful when a new FieldLayout is set and saved; the FieldLayout
 * Id can be retrieved without needing to explicitly set the newly created Id.
 *
 * @property FieldLayout|null $fieldLayout
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
    abstract protected static function fieldLayoutType(): string;

    /**
     * Internally set the FieldLayout Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetFieldLayoutId(int $id = null);

    /**
     * Internally get the FieldLayout Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetFieldLayoutId();

    /**
     * @return bool
     */
    public function isFieldLayoutSet(): bool
    {
        return null !== $this->fieldLayout;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setFieldLayoutId(int $id)
    {
        $this->internalSetFieldLayoutId($id);

        if (null !== $this->fieldLayout && $id != $this->fieldLayout->id) {
            $this->fieldLayout = null;
        }

        return $this;
    }

    /**
     * Get associated fieldLayoutId
     *
     * @return int|null
     */
    public function getFieldLayoutId()
    {
        if (null === $this->internalGetFieldLayoutId() && null !== $this->fieldLayout) {
            $this->setFieldLayoutId($this->fieldLayout->id);
        }

        return $this->internalGetFieldLayoutId();
    }

    /**
     * @param mixed $fieldLayout
     * @return $this
     */
    public function setFieldLayout($fieldLayout = null)
    {
        $this->fieldLayout = null;
        $this->internalSetFieldLayoutId(null);

        if (null !== ($fieldLayout = $this->verifyFieldLayout($fieldLayout))) {
            $this->fieldLayout = $fieldLayout;
            $this->internalSetFieldLayoutId($fieldLayout->id);
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

            $this->setFieldLayout($fieldLayout)
                ->setTypeOnFieldLayout($fieldLayout);

            return $fieldLayout;
        }

        $fieldLayoutId = $this->internalGetFieldLayoutId();
        if ($fieldLayoutId !== null && $fieldLayoutId != $this->fieldLayout->id) {
            $this->fieldLayout = null;
            return $this->getFieldLayout();
        }

        $this->setTypeOnFieldLayout($this->fieldLayout);
        return $this->fieldLayout;
    }

    /**
     * @param FieldLayout $fieldLayout
     */
    protected function setTypeOnFieldLayout(FieldLayout $fieldLayout)
    {
        if ($fieldLayout->type === null) {
            $fieldLayout->type = static::fieldLayoutType();
        }
    }

    /**
     * @return FieldLayout|null
     */
    protected function resolveFieldLayout()
    {
        if ($fieldLayout = $this->resolveFieldLayoutFromId()) {
            return $fieldLayout;
        }

        return $this->resolveFieldLayoutFromType();
    }

    /**
     * @return FieldLayout|null
     */
    private function resolveFieldLayoutFromId()
    {
        if (null === ($fieldLayoutId = $this->internalGetFieldLayoutId())) {
            return null;
        }

        return Craft::$app->getFields()->getLayoutById($fieldLayoutId);
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
    protected function verifyFieldLayout($fieldLayout = null)
    {
        if (null === $fieldLayout) {
            return null;
        }

        if ($fieldLayout instanceof FieldLayout) {
            return $fieldLayout;
        }

        if (is_numeric($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutById($fieldLayout);
        }

        if (is_string($fieldLayout)) {
            return Craft::$app->getFields()->getLayoutByType($fieldLayout);
        }

        return null;
    }
}
