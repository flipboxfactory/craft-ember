<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use flipbox\craft\ember\actions\PopulateTrait;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ActiveRecord populate(ActiveRecord $record)
 */
trait SaveRecordTrait
{
    use PopulateTrait,
        ManageRecordTrait;

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
