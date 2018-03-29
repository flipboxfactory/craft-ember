<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\record\traits;

use flipbox\ember\actions\traits\CheckAccess;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait View
{
    use CheckAccess;

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
