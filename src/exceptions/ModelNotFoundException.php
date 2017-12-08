<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\exceptions;

use yii\base\ErrorException as Exception;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ModelNotFoundException extends Exception
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Model Not Found Exception';
    }
}
