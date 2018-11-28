<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class ViewElement extends Action
{
    use ViewElementTrait, LookupElementTrait;

    /**
     * @inheritdoc
     */
    protected function find($identifier)
    {
        return $this->findById($identifier);
    }
}
