<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\actions\model;

use flipbox\ember\actions\traits\Index;
use yii\base\Action;
use yii\data\DataProviderInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class ModelIndex extends Action
{
    use Index;

    /**
     * @return DataProviderInterface
     */
    public function run(): DataProviderInterface
    {
        return $this->runInternal(
            $this->assembleDataProvider()
        );
    }
}
