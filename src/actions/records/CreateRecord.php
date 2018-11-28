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
     * @param array $config
     * @return ActiveRecord
     */
    abstract protected function newRecord(array $config = []): ActiveRecord;

    /**
     * @return ActiveRecord
     * @throws \yii\web\HttpException
     */
    public function run()
    {
        return $this->runInternal($this->newRecord());
    }

    /**
     * @inheritdoc
     */
    public function statusCodeSuccess(): int
    {
        return $this->statusCodeSuccess ?: 201;
    }
}
