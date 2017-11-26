<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\services\traits;

use Craft;
use craft\events\ModelEvent;
use flipbox\ember\interfaces\IdAttributeInterface;
use flipbox\ember\models\Model;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ModelSave
{

    /*******************************************
     * ABSTRACTS
     *******************************************/

    /**
     * @param Model $model
     * @param bool $mirrorScenario
     * @return Record
     */
    abstract protected function modelToRecord(Model $model, bool $mirrorScenario = true): Record;

    /**
     * @param Model $model
     * @return bool
     */
    abstract protected function isNew(Model $model): bool;

    /*******************************************
     * SAVE
     *******************************************/

    /**
     * @param Model $model
     * @param bool $runValidation
     * @param null $attributes
     * @param bool $mirrorScenario
     * @return bool
     * @throws \Exception
     */
    public function save(Model $model, bool $runValidation = true, $attributes = null, bool $mirrorScenario = true)
    {
        // Validate
        if ($runValidation && !$model->validate($attributes)) {
            Craft::info('Model not saved due to validation error.', __METHOD__);
            return false;
        }

        $isNew = $this->isNew($model);

        // a 'beforeSave' event
        if (!$this->beforeSave($model, $isNew)) {
            return false;
        }

        // Create event
        $event = new ModelEvent([
            'isNew' => $isNew
        ]);

        // Db transaction
        $transaction = Craft::$app->getDb()->beginTransaction();

        try {
            // The 'before' event
            if (!$model->beforeSave($event)) {
                $transaction->rollBack();

                return false;
            }

            $record = $this->modelToRecord($model, $mirrorScenario);

            // Validate
            if (!$record->validate($attributes)) {
                $model->addErrors($record->getErrors());

                $transaction->rollBack();

                return false;
            }

            // Insert record
            if (!$record->save($attributes)) {
                // Transfer errors to model
                $model->addErrors($record->getErrors());

                $transaction->rollBack();

                return false;
            }

            // Transfer attributes to model
            $this->transferFromRecord(
                $model,
                $record,
                $isNew
            );


            // The 'after' event
            if (!$model->afterSave($event)) {
                $transaction->rollBack();

                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }

        $transaction->commit();

        // an 'afterSave' event
        $this->afterSave($model, $isNew);

        return true;
    }

    /**
     * @param Model $model
     * @param bool $isNew
     * @return bool
     */
    protected function beforeSave(Model $model, bool $isNew): bool
    {
        return true;
    }

    /**
     * @param Model $model
     * @param bool $isNew
     */
    protected function afterSave(Model $model, bool $isNew)
    {

        Craft::info(sprintf(
            "Model '%s' was saved successfully.",
            (string)get_class($model)
        ), __METHOD__);
    }

    /**
     * @param Model $model
     * @param Record $record
     * @param bool $isNew
     * @return void
     */
    protected function transferFromRecord(Model $model, Record $record, bool $isNew)
    {

        // Transfer record to model
        if ($isNew) {
            if ($model instanceof IdAttributeInterface && $record instanceof IdAttributeInterface) {
                $model->setId($record->getId());
            }
        }

        $this->transferAuditAttributes($model, $record, $isNew);
    }

    /**
     * @param Model $model
     * @param Record $record
     * @param bool $isNew
     */
    protected function transferAuditAttributes(Model $model, Record $record, bool $isNew)
    {
        if ($isNew) {
            if ($record->hasAttribute('dateCreated')) {
                $model->setDateCreated($record->getAttribute('dateCreated'));
            }

            if ($record->hasAttribute('uid')) {
                $model->uid = $record->getAttribute('uid');
            }
        }

        if ($record->hasAttribute('dateUpdated')) {
            $model->setDateUpdated($record->getAttribute('dateUpdated'));
        }
    }
}
