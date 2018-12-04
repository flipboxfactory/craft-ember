<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use Craft;
use craft\base\ElementInterface;
use yii\base\Action;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
abstract class UpdateElement extends Action
{
    use SaveElementTrait, LookupElementTrait;

    /**
     * @var array
     */
    public $validBodyParams = [];

    /**
     * Body params that should be set on the record.
     *
     * @return array
     */
    protected function validBodyParams(): array
    {
        return $this->validBodyParams;
    }

    /**
     * @inheritdoc
     * @param ElementInterface $element
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    protected function performAction(ElementInterface $element): bool
    {
        return Craft::$app->getElements()->saveElement($element);
    }
}
