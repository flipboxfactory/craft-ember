<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/domains/license
 * @link       https://www.flipboxfactory.com/software/domains/
 */

namespace flipbox\ember\db\traits;

use yii\base\BaseObject;

/**
 * @property string|callable $indexBy
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait PopulateObject
{
    /**
     * @param $row
     *
     * @return BaseObject
     */
    abstract protected function createObject($row): BaseObject;

    /**
     * @inheritdoc
     *
     * @return BaseObject[]|array The resulting elements.
     */
    public function populate($rows)
    {
        $indexBy = $this->indexBy;

        if ($indexBy === null) {
            return $this->createObjects($rows);
        }
        $result = [];
        foreach ($rows as $row) {
            if (is_string($indexBy)) {
                $key = $row[$indexBy];
            } else {
                $key = call_user_func($indexBy, $row);
            }
            $result[$key] = $this->createObject($row);
        }
        return $result;
    }

    /**
     * @param $rows
     *
     * @return mixed
     */
    protected function createObjects($rows)
    {
        $models = [];

        foreach ($rows as $key => $row) {
            $models[$key] = $this->createObject($row);
        }

        return $models;
    }
}
