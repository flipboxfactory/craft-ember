<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use yii\base\BaseObject;

/**
 * @property string|callable $indexBy
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait PopulateObjectTrait
{
    /**
     * @param $row
     *
     * @return BaseObject
     */
    abstract protected function createObject($row);

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
