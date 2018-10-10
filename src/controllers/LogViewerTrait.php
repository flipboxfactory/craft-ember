<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\controllers;

use Craft;
use craft\helpers\DateTimeHelper;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Pagination;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.8
 */
trait LogViewerTrait
{
    /**
     * @return string
     */
    protected abstract function logFile(): string;

    /**
     * @return string
     */
    protected function lineRegex(): string
    {
        return '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) \[(.*)\]\[(.*)\]\[(.*)\]\[(.*)\]\[(.*)\] (.*)/';
    }

    /**
     * @param array $matches
     * @param array $log
     * @return array
     */
    protected function lineMatcher(array $matches, array $log): array
    {
        return [
            'time' => DateTimeHelper::toDateTime($matches[1]),
            'message' => $matches[7],
            'ip' => $matches[2],
            'userId' => $matches[3],
            'sessionId' => $matches[4],
            'level' => $matches[5],
            'category' => $matches[6],
            'vars' => ''
        ];
    }

    /**
     * @param string $line
     * @param array $log
     * @return array
     */
    protected function lineExtra(string $line, array $log): array
    {
        $log['vars'] .= utf8_encode($line);

        return $log;
    }

    /**
     * @return DataProviderInterface
     * @throws \Exception
     */
    protected function getLogItems(): DataProviderInterface
    {
        $file = Craft::getAlias($this->logFile());

        if (!is_file($file)) {
            throw new \Exception("'$file' is not found");
        }

        return new ArrayDataProvider([
            'allModels' => $this->parseFile($file),
            'sort' => [
                'attributes' => [
                    'time' => ['default' => SORT_DESC],
                    'level' => ['default' => SORT_DESC]
                ],
            ],
            'pagination' => [
                'class' => Pagination::class,
                'pageSizeParam' => 'size',
                'pageParam' => 'page',
                'pageSizeLimit' => 'limit',
                'defaultPageSize' => 200,
            ]
        ]);
    }

    /**
     * @param string
     */
    protected function parseFile($file)
    {
        $lines = [];
        $log = [];
        if ($file = fopen($file, "r")) {
            while (($line = fgets($file)) !== false) {
                // Log line
                if (preg_match($this->lineRegex(), $line, $matches)) {
                    if (!empty($log)) {
                        $lines[] = $log;
                    }
                    $log = $this->lineMatcher($matches, $log);
                } else {
                    $log = $this->lineExtra($line, $log);
                }
            }
            fclose($file);
        }
        return $lines;
    }
}
