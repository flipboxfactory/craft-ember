<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use flipbox\craft\ember\actions\CheckAccessTrait;
use flipbox\craft\ember\actions\ResponseTrait;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ActiveRecord traitRunInternal(ActiveRecord $record)
 */
trait ManageRecordTrait
{
    use CheckAccessTrait, ResponseTrait;

    /**
     * @param ActiveRecord $record
     * @return bool
     */
    abstract protected function performAction(ActiveRecord $record): bool;

    /**
     * @param ActiveRecord $data
     * @return mixed
     * @throws \yii\web\HttpException
     */
    protected function runInternal(ActiveRecord $data)
    {
        // Check access
        if (($access = $this->checkAccess($data)) !== true) {
            return $access;
        }

        if (!$this->performAction($data)) {
            return $this->handleFailResponse($data);
        }

        return $this->handleSuccessResponse($data);
    }
}
