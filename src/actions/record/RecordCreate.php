<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\record;

use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class RecordCreate extends Action
{
    use traits\Save;

    /**
     * @param array $config
     * @return ActiveRecord
     */
    abstract protected function newRecord(array $config = []): ActiveRecord;

    /**
     * @return ActiveRecord|null|Response
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
