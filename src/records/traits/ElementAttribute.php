<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\records\traits;

use Craft;
use craft\base\ElementInterface;
use craft\records\Element as ElementRecord;
use flipbox\ember\traits\ElementRules;
use flipbox\ember\traits\ElementMutator;
use yii\db\ActiveQueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementAttribute
{
    use ActiveRecord,
        ElementRules,
        ElementMutator;
    
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

        /** @var ElementRecord $record */
        $record = $this->getRelation('elementRecord');
        if (null === $record) {
            return null;
        }

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
