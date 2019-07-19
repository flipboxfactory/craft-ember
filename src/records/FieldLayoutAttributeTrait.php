<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\models\FieldLayout as FieldLayoutModel;
use craft\records\FieldLayout as FieldLayoutRecord;
use flipbox\craft\ember\models\FieldLayoutRulesTrait;
use flipbox\craft\ember\objects\FieldLayoutMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property FieldLayoutRecord $fieldLayoutRecord
 */
trait FieldLayoutAttributeTrait
{
    use ActiveRecordTrait,
        FieldLayoutRulesTrait,
        FieldLayoutMutatorTrait {
        resolveFieldLayout as parentResolveFieldLayout;
    }

    /**
     * @inheritdoc
     */
    public function fieldLayoutAttributes(): array
    {
        return [
            'fieldLayoutId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function fieldLayoutAttributeLabels(): array
    {
        return [
            'fieldLayoutId' => Craft::t('app', 'Field Layout Id')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetFieldLayoutId(int $id = null)
    {
        $this->setAttribute('fieldLayoutId', $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetFieldLayoutId()
    {
        if (null === ($id = $this->getAttribute('fieldLayoutId'))) {
            return null;
        }
        return (int)$id;
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

        if (null === ($record = $this->getRelation('fieldLayoutRecord'))) {
            return null;
        }

        /** @var FieldLayoutRecord $record */

        return Craft::$app->getFields()->getLayoutById($record->id);
    }

    /**
     * Get the associated Field Layout
     *
     * @return ActiveQueryInterface
     */
    public function getFieldLayoutRecord(): ActiveQueryInterface
    {
        return $this->hasOne(
            FieldLayoutRecord::class,
            ['id' => 'fieldLayoutId']
        );
    }
}
