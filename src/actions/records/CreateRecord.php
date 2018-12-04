<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use yii\base\Action;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class CreateRecord extends Action
{
    use SaveRecordTrait;

    /**
     * @var array
     */
    public $validBodyParams = [];

    /**
     * @inheritdoc
     */
    public $statusCodeSuccess = 201;

    /**
     * @param array $config
     * @return ActiveRecord
     */
    abstract protected function newRecord(array $config = []): ActiveRecord;

    /**
     * Body params that should be set on the record.
     *
     * @return array
     */
    protected function validBodyParams(): array
    {
        return $this->validBodyParams;
    }

    /**
     * @param ActiveRecord $record
     * @return ActiveRecord
     */
    protected function populate(ActiveRecord $record): ActiveRecord
    {
        $record->setAttributes(
            $this->attributeValuesFromBody()
        );

        return $record;
    }

    /**
     * @return ActiveRecord
     * @throws \yii\web\HttpException
     */
    public function run()
    {
        return $this->runInternal(
            $this->newRecord()
        );
    }

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     */
    protected function performAction(ActiveRecord $record): bool
    {
        return $record->save();
    }
}
