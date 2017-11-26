<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\actions\traits;

use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Index
{
    use PrepareData, CheckAccess;

    /**
     * @param array $config
     * @return QueryInterface
     */
    abstract protected function assembleQuery(array $config = []): QueryInterface;

    /**
     * @return DataProviderInterface
     */
    public function run(): DataProviderInterface
    {
        return $this->runInternal(
            $this->assembleDataProvider()
        );
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return DataProviderInterface
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
     */
    protected function assembleDataProvider(array $config = []): DataProviderInterface
    {
        return new ActiveDataProvider([
            'query' => $this->assembleQuery(
                $this->normalizeQueryConfig($config)
            )
        ]);
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
