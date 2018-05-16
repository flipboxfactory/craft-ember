<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\record\traits;

use flipbox\ember\actions\traits\Populate;
use yii\db\ActiveRecord;

/**
 * @method ActiveRecord populate(ActiveRecord $record)
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Save
{
    use Populate, Manage;

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     */
    public function runInternal(ActiveRecord $record)
    {
        // Populate
        $this->populate($record);

        // Check access
        if (($access = $this->checkAccess($record)) !== true) {
            return $access;
        }

        if (!$this->performAction($record)) {
            return $this->handleFailResponse($record);
        }

        return $this->handleSuccessResponse($record);
    }
}
