<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\record\traits;

use flipbox\ember\actions\traits\Lookup as BaseLookup;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Lookup
{
    use BaseLookup;

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     * @return ActiveRecord|Response
     */
    abstract protected function runInternal(ActiveRecord $record);
}
