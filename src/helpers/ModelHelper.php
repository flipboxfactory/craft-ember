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
     * @deprecated Use `yii\base\Model::SCENARIO_DEFAULT`
     */
    const DEFAULT_SCENARIO = Model::SCENARIO_DEFAULT;

    /**
     * The scenario used by default
     * @deprecated Use `yii\base\Model::SCENARIO_DEFAULT`
     */
    const SCENARIO_DEFAULT = Model::SCENARIO_DEFAULT;
}
