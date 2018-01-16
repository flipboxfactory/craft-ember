<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\actions\model;

use flipbox\ember\actions\traits\Index;
use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ModelIndex extends Action
{
    use Index;
}
