<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use Craft;
use craft\base\ElementInterface;
use flipbox\craft\ember\actions\LookupTrait;
use yii\web\Response;

trait LookupElementTrait
{
    use LookupTrait;

    /**
     * @inheritdoc
     * @param ElementInterface $element
     * @return ElementInterface|Response
     */
    abstract protected function runInternal(ElementInterface $element);

    /**
     * @param int $id
     * @return null|ElementInterface
     */
    protected function findById(int $id)
    {
        return Craft::$app->getElements()->getElementById($id);
    }
}
