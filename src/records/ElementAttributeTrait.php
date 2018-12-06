<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\records;

use Craft;
use craft\base\ElementInterface;
use craft\records\Element as ElementRecord;
use flipbox\craft\ember\models\ElementRulesTrait;
use flipbox\craft\ember\objects\ElementMutatorTrait;
use yii\db\ActiveQueryInterface;

/**
 * Intended to be used on an ActiveRecord, this class provides `$this->elementId` attribute along with 'getters'
 * and 'setters' to ensure continuity between the Id and Object.  An element object is lazy loaded when called.
 * In addition, ActiveRecord rules are available.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementAttributeTrait
{
    use ActiveRecordTrait,
        ElementRulesTrait,
        ElementMutatorTrait;

    /**
     * @inheritdoc
     */
    public function elementAttributes(): array
    {
        return [
            'elementId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function elementAttributeLabels(): array
    {
        return [
            'elementId' => Craft::t('app', 'Element Id')
        ];
    }

    /**
     * Get associated elementId
     *
     * @return int|null
     */
    public function getElementId()
    {
        $id = $this->getAttribute('elementId');
        if (null === $id && null !== $this->element) {
            $id = $this->elementId = $this->element->id;
        }

        return $id;
    }

    /**
     * @return ElementInterface|null
     */
    protected function resolveElement()
    {
        if ($model = $this->resolveElementFromRelation()) {
            return $model;
        }

        return $this->resolveElementFromId();
    }

    /**
     * @return ElementInterface|null
     */
    private function resolveElementFromRelation()
    {
        if (false === $this->isRelationPopulated('elementRecord')) {
            return null;
        }

        if (null === ($record = $this->getRelation('elementRecord'))) {
            return null;
        }

        /** @var ElementRecord $record */

        return Craft::$app->getElements()->getElementById($record->id);
    }

    /**
     * Get the associated Element
     *
     * @return ActiveQueryInterface
     */
    public function getElementRecord()
    {
        return $this->hasOne(
            ElementRecord::class,
            ['elementId' => 'id']
        );
    }
}
