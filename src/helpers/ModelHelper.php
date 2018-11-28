<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\helpers;

use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class ModelHelper
{
    /**
     * The default scenario
     */
    const DEFAULT_SCENARIO = self::SCENARIO_DEFAULT;

    /**
     * The scenario used by default
     */
    const SCENARIO_DEFAULT = Model::SCENARIO_DEFAULT;
}
