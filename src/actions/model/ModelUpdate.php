<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\actions\model;

use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ModelUpdate extends Action
{
    use traits\Save, traits\Lookup;
}
