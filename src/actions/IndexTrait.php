<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use craft\helpers\ArrayHelper;
use flipbox\craft\ember\helpers\ObjectHelper;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait IndexTrait
{
    use PrepareDataTrait, CheckAccessTrait;

    /**
     * @var array
     */
    public $dataProvider = [];

    /**
     * @param array $config
     * @return QueryInterface
     */
    abstract protected function createQuery(array $config = []): QueryInterface;

    /**
     * @return DataProviderInterface
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function run(): DataProviderInterface
    {
        return $this->runInternal(
            $this->createDataProvider()
        );
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return DataProviderInterface
     * @throws \yii\web\HttpException
     */
    protected function runInternal(DataProviderInterface $dataProvider): DataProviderInterface
    {
        // Check access
        if (($access = $this->checkAccess($dataProvider)) !== true) {
            return $access;
        }

        return $this->performAction($dataProvider);
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return DataProviderInterface
     */
    protected function performAction(DataProviderInterface $dataProvider): DataProviderInterface
    {
        // Allow alterations to the data
        $this->prepareData($dataProvider);

        return $dataProvider;
    }

    /**
     * @param array $config
     * @return DataProviderInterface
     * @throws \yii\base\InvalidConfigException
     */
    protected function createDataProvider(array $config = []): DataProviderInterface
    {
        $queryConfig = (array) ArrayHelper::remove($config, 'query', []);

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = ObjectHelper::create(
            $this->dataProviderConfig(ArrayHelper::merge(
                [
                    'query' => $this->createQuery($queryConfig)
                ],
                $config
            )),
            DataProviderInterface::class
        );

        return $dataProvider;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function dataProviderConfig(array $config = []): array
    {
        return array_merge(
            [
                'class' => ActiveDataProvider::class
            ],
            $config,
            $this->dataProvider
        );
    }
}
