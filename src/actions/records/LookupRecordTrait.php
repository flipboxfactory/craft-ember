<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use flipbox\craft\ember\actions\LookupTrait;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait LookupRecordTrait
{
    use LookupTrait;

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     * @return ActiveRecord|Response
     */
    abstract protected function runInternal(ActiveRecord $record);
}
