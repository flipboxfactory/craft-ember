<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use flipbox\craft\ember\actions\CheckAccessTrait;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ViewRecordTrait
{
    use CheckAccessTrait;

    /**
     * @param ActiveRecord $record
     * @return ActiveRecord
     * @throws \yii\web\UnauthorizedHttpException
     */
    protected function runInternal(ActiveRecord $record)
    {
        // Check access
        if (($access = $this->checkAccess($record)) !== true) {
            return $access;
        }

        return $record;
    }
}
