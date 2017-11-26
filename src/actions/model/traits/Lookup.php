<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\actions\model\traits;

use flipbox\ember\actions\traits\Lookup as BaseLookup;
use yii\base\Model;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Lookup
{
    use BaseLookup;

    /**
     * @param Model $model
     * @return Model|Response
     */
    abstract public function runInternal(Model $model);
}
