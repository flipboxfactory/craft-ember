<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\records\traits;

use craft\models\FieldLayout as FieldLayoutModel;
use craft\records\FieldLayout as FieldLayoutRecord;
use flipbox\ember\helpers\FieldLayoutHelper;
use flipbox\ember\traits\FieldLayoutMutator;
use flipbox\ember\traits\FieldLayoutRules;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait FieldLayoutAttribute
{
    use ActiveRecord,
        FieldLayoutRules,
        FieldLayoutMutator {
        resolveFieldLayout as parentResolveFieldLayout;
    }

    /**
     * Get associated fieldLayoutId
     *
     * @return int|null
     */
    public function getFieldLayoutId()
    {
        $fieldLayoutId = $this->getAttribute('fieldLayoutId');
        if (null === $fieldLayoutId && null !== $this->fieldLayout) {
            $fieldLayoutId = $this->fieldLayoutId = $this->fieldLayout->id;
        }

        return $fieldLayoutId;
    }

    /**
     * @return FieldLayoutModel|null
     */
    protected function resolveFieldLayout()
    {
        if ($fieldLayout = $this->resolveFieldLayoutFromRelation()) {
            return $fieldLayout;
        }

        return $this->parentResolveFieldLayout();
    }

    /**
     * @return FieldLayoutModel|null|object
     */
    private function resolveFieldLayoutFromRelation()
    {
        if (false === $this->isRelationPopulated('fieldLayoutRecord')) {
            return null;
        }

        return FieldLayoutHelper::resolve($this->getRelation('fieldLayoutRecord'));
    }

    /**
     * Get the associated Field Layout
     *
     * @return ActiveQueryInterface
     */
    public function getFieldLayoutRecord()
    {
        return $this->hasOne(
            FieldLayoutRecord::class,
            ['fieldLayoutId' => 'id']
        );
    }
}
