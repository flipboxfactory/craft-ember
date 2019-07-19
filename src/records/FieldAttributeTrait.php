<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\records\Field as FieldRecord;
use flipbox\craft\ember\models\FieldRulesTrait;
use flipbox\craft\ember\objects\FieldMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->userId` attribute along with 'getters'
 * and 'setters' to ensure continuity between the Id and Object.  An user object is lazy loaded when called.
 * In addition, ActiveRecord rules are available.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @property FieldRecord $fieldRecord
 */
trait FieldAttributeTrait
{
    use ActiveRecordTrait,
        FieldRulesTrait,
        FieldMutatorTrait;

    /**
     * @inheritdoc
     */
    public function fieldAttributes(): array
    {
        return [
            'fieldId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function fieldAttributeLabels(): array
    {
        return [
            'fieldId' => Craft::t('app', 'Field Id')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetFieldId(int $id = null)
    {
        $this->setAttribute('fieldId', $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetFieldId()
    {
        if (null === ($id = $this->getAttribute('fieldId'))) {
            return null;
        }
        return (int)$id;
    }

    /**
     * @return FieldInterface|Field|null
     */
    protected function resolveField()
    {
        if ($field = $this->resolveFieldFromRelation()) {
            return $field;
        }

        return $this->resolveFieldFromId();
    }

    /**
     * @return FieldInterface|Field|null
     */
    private function resolveFieldFromRelation()
    {
        if (false === $this->isRelationPopulated('fieldRecord')) {
            return null;
        }

        if (null === ($record = $this->getRelation('fieldRecord'))) {
            return null;
        }

        /** @var FieldRecord $record */

        return Craft::$app->getFields()->getFieldById($record->id);
    }

    /**
     * Get the associated Field record
     *
     * @return ActiveQueryInterface
     */
    public function getFieldRecord(): ActiveQueryInterface
    {
        return $this->hasOne(
            FieldRecord::class,
            ['id' => 'fieldId']
        );
    }
}
