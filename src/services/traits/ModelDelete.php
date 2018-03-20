<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use Craft;
use flipbox\ember\models\Model;
use yii\base\ModelEvent;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ModelDelete
{

    /*******************************************
     * ABSTRACTS
     *******************************************/

    /**
     * @param Model $model
     * @return Record
     */
    abstract public function getRecordByModel(Model $model): Record;


    /*******************************************
     * DELETE
     *******************************************/

    /**
     * @param Model $model
     * @return bool
     * @throws \Exception
     */
    public function delete(Model $model): bool
    {

        // a 'beforeSave' event
        if (!$this->beforeDelete($model)) {
            return false;
        }

        // The event to trigger
        $event = new ModelEvent();

        // Db transaction
        $transaction = Craft::$app->getDb()->beginTransaction();

        try {
            // The 'before' event
            if (!$model->beforeDelete($event)) {
                $transaction->rollBack();
                return false;
            }

            // Get record
            /** @var Record $record */
            $record = $this->getRecordByModel($model);

            // Insert record
            if (false === $record->delete()) {
                $model->addErrors($record->getErrors());
                $transaction->rollBack();
                return false;
            }

            // The 'after' event
            if (!$model->afterDelete($event)) {
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        $transaction->commit();
        $this->afterDelete($model);
        return true;
    }

    /**
     * @param Model $model
     * @return bool
     */
    protected function beforeDelete(Model $model): bool
    {
        return true;
    }

    /**
     * @param Model $model
     */
    protected function afterDelete(Model $model)
    {
        Craft::info(sprintf(
            "Model '%s' was deleted successfully.",
            (string)get_class($model)
        ), __METHOD__);
    }
}
