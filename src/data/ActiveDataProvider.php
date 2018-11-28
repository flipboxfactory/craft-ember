<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\data;

use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * Ref: https://github.com/craftcms/cms/issues/2857
 */
class ActiveDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    protected function prepareModels()
    {
        $query = $this->getQueryClone();
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            if ($pagination->totalCount === 0) {
                return [];
            }
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        return $query->all($this->db);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    protected function prepareTotalCount()
    {
        $query = $this->getQueryClone();
        return (int)$query->limit(-1)->offset(-1)->orderBy([])->count('*', $this->db);
    }

    /**
     * @return QueryInterface
     * @throws InvalidConfigException
     */
    private function getQueryClone(): QueryInterface
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements 
            the QueryInterface e.g. yii\db\Query or its subclasses.');
        }

        $query = clone $this->query;

        if ($this->query instanceof Query) {
            /** @var Query $query */
            foreach ($this->query->getBehaviors() as $name => $behavior) {
                $query->attachBehavior($name, clone $behavior);
            }
        }

        return $query;
    }
}
