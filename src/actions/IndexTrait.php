<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\actions;

use flipbox\craft\ember\data\ActiveDataProvider;
use flipbox\craft\ember\helpers\ObjectHelper;
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
        /** @var DataProviderInterface $dataProvider */
        $dataProvider = ObjectHelper::create(
            $this->dataProviderConfig([
                'query' => $this->createQuery(
                    $this->normalizeQueryConfig($config)
                )
            ]),
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

    /**
     * @param array $config
     * @return array
     */
    protected function normalizeQueryConfig(array $config = []): array
    {
        return $config;
    }
}
