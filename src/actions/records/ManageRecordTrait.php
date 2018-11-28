<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\records;

use flipbox\craft\ember\actions\ManageTrait;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method ActiveRecord traitRunInternal(ActiveRecord $record)
 */
trait ManageRecordTrait
{
    use ManageTrait {
        runInternal as traitRunInternal;
    }

    /**
     * @param ActiveRecord $record
     * @return bool
     */
    abstract protected function performAction(ActiveRecord $record): bool;

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     * @return ActiveRecord
     */
    protected function runInternal(ActiveRecord $record)
    {
        return $this->traitRunInternal($record);
    }
}
