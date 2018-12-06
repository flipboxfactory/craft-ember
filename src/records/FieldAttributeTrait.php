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
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
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
     * Get associated fieldId
     *
     * @return int|null
     */
    public function getFieldId()
    {
        $fieldId = $this->getAttribute('fieldId');
        if (null === $fieldId && null !== $this->field) {
            $fieldId = $this->$fieldId = $this->field->id;
        }

        return $fieldId;
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
    public function getFieldRecord()
    {
        return $this->hasOne(
            FieldRecord::class,
            ['fieldId' => 'id']
        );
    }
}
