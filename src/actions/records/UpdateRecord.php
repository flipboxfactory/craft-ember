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
abstract class UpdateRecord extends Action
{
    use SaveRecordTrait, LookupRecordTrait;

    /**
     * @var array
     */
    public $validBodyParams = [];

    /**
     * Body params that should be set on the record.
     *
     * @return array
     */
    protected function validBodyParams(): array
    {
        return $this->validBodyParams;
    }

    /**
     * @param ActiveRecord $record
     * @return ActiveRecord
     */
    protected function populate(ActiveRecord $record): ActiveRecord
    {
        $record->setAttributes(
            $this->attributeValuesFromBody()
        );

        return $record;
    }

    /**
     * @inheritdoc
     * @param ActiveRecord $record
     */
    protected function performAction(ActiveRecord $record): bool
    {
        return $record->save();
    }
}
