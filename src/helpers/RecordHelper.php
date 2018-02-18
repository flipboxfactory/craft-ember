<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\helpers;

use flipbox\ember\records\Record;
use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RecordHelper
{
    /**
     * @param $condition
     * @return array
     */
    public static function conditionToCriteria($condition)
    {
        if (empty($condition)) {
            return $condition;
        }

        // Assume it's an id
        if (!is_array($condition)) {
            $condition = [
                'id' => $condition
            ];
        }

        return ['where' => ['and', $condition]];
    }
}
