<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\services\queries;

use Craft;
use flipbox\craft\ember\exceptions\NotFoundException;
use yii\caching\Dependency;
use yii\db\Connection;
use yii\db\QueryInterface;

/**
 * A set of robust methods commonly used to retrieve data from the attached database.  An optional
 * cache layer can be applied to circumvent heavy queries.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait BaseQueryAccessor
{
    /**
     * @var int|null|false
     */
    protected $cacheDuration = false;

    /**
     * @var null|Dependency
     */
    protected $cacheDependency = null;

    /*******************************************
     * QUERY
     *******************************************/

    /**
     * @param array $config
     * @return \yii\db\ActiveQuery
     */
    abstract public function getQuery($config = []): QueryInterface;

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @param $duration
     * @return $this
     */
    public function setCacheDuration($duration)
    {
        if (is_numeric($duration)) {
            $duration = (int)$duration;
        }

        $this->cacheDuration = $duration;
        return $this;
    }

    /**
     * @param Dependency|null $dependency
     * @return $this
     */
    public function setCacheDependency(Dependency $dependency = null)
    {
        $this->cacheDependency = $dependency;
        return $this;
    }

    /**
     * @return Connection
     */
    protected static function getDb(): Connection
    {
        return Craft::$app->getDb();
    }

    /*******************************************
     * ONE QUERY
     *******************************************/

    /**
     * @param QueryInterface $query
     * @return mixed|null
     */
    public function findByQuery(QueryInterface $query)
    {
        return $this->queryOne($query);
    }

    /**
     * @param QueryInterface $query
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getByQuery(QueryInterface $query)
    {
        if (null === ($object = $this->findByQuery($query))) {
            $this->notFoundException();
        }

        return $object;
    }

    /*******************************************
     * ALL BY QUERY
     *******************************************/

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function findAllByQuery(QueryInterface $query): array
    {
        return $this->queryAll($query);
    }

    /**
     * @param QueryInterface $query
     * @return array
     * @throws NotFoundException
     */
    public function getAllByQuery(QueryInterface $query): array
    {
        $records = $this->findAllByQuery($query);
        if (empty($records)) {
            $this->notFoundException();
        }

        return $records;
    }


    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param QueryInterface $query
     * @return mixed|null
     */
    protected function queryOne(QueryInterface $query)
    {
        $db = static::getDb();

        try {
            if (false === $this->cacheDuration) {
                return $query->one($db);
            }

            /** @noinspection PhpUnhandledExceptionInspection */
            $result = $db->cache(function ($db) use ($query) {
                return $query->one($db);
            }, $this->cacheDuration, $this->cacheDependency);
        } catch (\Exception $e) {
            return null;
        }

        return $result;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param QueryInterface $query
     * @return mixed[]
     */
    protected function queryAll(QueryInterface $query)
    {
        $db = static::getDb();

        try {
            if (false === $this->cacheDuration) {
                return $query->all($db);
            }

            /** @noinspection PhpUnhandledExceptionInspection */
            $results = $db->cache(function ($db) use ($query) {
                return $query->all($db);
            }, $this->cacheDuration, $this->cacheDependency);
        } catch (\Exception $e) {
            return [];
        }

        return $results;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws NotFoundException
     */
    protected function notFoundException()
    {
        throw new NotFoundException(
            sprintf(
                "Results not found."
            )
        );
    }
}
