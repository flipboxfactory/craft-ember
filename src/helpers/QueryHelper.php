<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\helpers;

use craft\helpers\ArrayHelper;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class QueryHelper
{
    /**
     * @param QueryInterface $query
     * @param array $config
     * @return QueryInterface
     */
    public static function configure(QueryInterface $query, $config = []): QueryInterface
    {
        // Halt
        if (empty($config)) {
            return $query;
        }

        // Force array
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        // Populate query attributes
        foreach ($config as $name => $value) {
            if (property_exists($query, $name)) {
                $query->$name = $value;
            } elseif (method_exists($query, 'set' . $name)) {
                // set property
                $query->{'set' . $name}($value);
            }
        }

        return $query;
    }
}
