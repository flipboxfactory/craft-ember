<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use yii\base\ArrayableTrait;

class CacheableActiveQuery extends ActiveQuery
{
    use ArrayableTrait;

    /**
     * @var array|null The cached query result
     * @see setCachedResult()
     */
    private $result;

    /**
     * @var array|null The criteria params that were set when the cached query result was set
     * @see setCachedResult()
     */
    private $resultCriteria;

    /**
     * @inheritdoc
     */
    public function count($q = '*', $db = null)
    {
        // Cached?
        if (($cachedResult = $this->getCachedResult()) !== null) {
            return count($cachedResult);
        }

        return parent::count($q, $db) ?: 0;
    }

    /**
     * @inheritdoc
     */
    public function all($db = null)
    {
        // Cached?
        if (($cachedResult = $this->getCachedResult()) !== null) {
            return $cachedResult;
        }

        return parent::all($db);
    }

    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
        if (($cachedResult = $this->getCachedResult()) !== null) {
            return reset($cachedResult);
        }

        return parent::one($db);
    }

    /**
     * Returns the resulting domains set by [[setCachedResult()]], if the criteria params haven’t changed since then.
     *
     * @return array|null The resulting domains, or null if setCachedResult() was never called or the criteria has
     * changed
     * @see setCachedResult()
     */
    public function getCachedResult()
    {
        if ($this->result === null) {
            return null;
        }

        // Make sure the criteria hasn't changed
        if ($this->resultCriteria !== $this->getCriteria()) {
            $this->result = null;

            return null;
        }

        return $this->result;
    }

    /**
     * Sets the resulting domains.
     *
     * If this is called, [[all()]] will return these domains rather than initiating a new SQL query,
     * as long as none of the parameters have changed since setCachedResult() was called.
     *
     * @param array $objects The resulting objects.
     *
     * @see getCachedResult()
     */
    public function setCachedResult(array $objects)
    {
        $this->result = $objects;
        $this->resultCriteria = $this->getCriteria();
    }

    /**
     * Returns an array of the current criteria attribute values.
     *
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->toArray($this->criteriaAttributes(), [], false);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * Returns the query's criteria attributes.
     *
     * @return string[]
     */
    public function criteriaAttributes(): array
    {
        // By default, include all public, non-static properties that were defined by a sub class, and certain ones
        // in this class
        /** @noinspection PhpUnhandledExceptionInspection */
        $class = new \ReflectionClass($this);
        $names = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $dec = $property->getDeclaringClass();
                if (($dec->getName() === self::class || $dec->isSubclassOf(self::class))
                ) {
                    $names[] = $property->getName();
                }
            }
        }

        return $names;
    }
}
