<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions\elements;

use Craft;
use craft\base\ElementInterface;
use flipbox\craft\ember\actions\NotFoundTrait;
use yii\web\HttpException;
use yii\web\Response;

trait LookupElementTrait
{
    use NotFoundTrait;

    /**
     * @inheritdoc
     * @param ElementInterface $element
     * @return ElementInterface|Response
     */
    abstract protected function runInternal(ElementInterface $element);

    /**
     * @param string|int $identifier
     * @return ElementInterface|null
     */
    abstract protected function find($identifier);

    /**
     * @param $identifier
     * @return mixed|null|Response
     * @throws HttpException
     */
    public function run($identifier)
    {
        if (!$object = $this->find($identifier)) {
            return $this->handleNotFoundResponse();
        }

        return $this->runInternal($object);
    }

    /**
     * @param int $id
     * @return null|ElementInterface
     */
    protected function findById(int $id)
    {
        return Craft::$app->getElements()->getElementById($id);
    }
}
