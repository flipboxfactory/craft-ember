<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\errors\ElementNotFoundException;
use flipbox\ember\helpers\SiteHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method ElementInterface parentFind($identifier, int $siteId = null, string $toScenario = null)
 * @method ElementInterface parentFindCache($identifier, int $siteId = null)
 */
trait ElementAccessorByString
{
    use ElementAccessor {
        find as parentFind;
        findCache as parentFindCache;
    }

    /**
     * @var [ElementInterface[]]
     */
    protected $cacheByString = [];

    /**
     * @return string
     */
    abstract protected function stringProperty(): string;

    /*******************************************
     * STRING
     *******************************************/

    /**
     * @param ElementInterface $element
     * @return string
     */
    protected function stringValue(ElementInterface $element)
    {
        $property = $this->stringProperty();

        return $element->{$property};
    }


    /*******************************************
     * FIND OVERRIDES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function find($identifier, int $siteId = null, string $toScenario = null)
    {
        if ($element = $this->parentFind($identifier, $siteId, $toScenario)) {
            return $element;
        }

        if (!is_string($identifier)) {
            return null;
        }

        return $this->findByString($identifier, $siteId, $toScenario);
    }

    /*******************************************
     * CACHE OVERRIDES
     *******************************************/

    /**
     * @inheritdoc
     */
    public function findCache($identifier, int $siteId = null)
    {
        if ($element = $this->parentFindCache($identifier, $siteId)) {
            return $element;
        }

        if (!is_string($identifier)) {
            return null;
        }

        return $this->findCacheByString($identifier, $siteId);
    }

    /**
     * @inheritdoc
     */
    public function addToCache(ElementInterface $element)
    {
        $this->cacheById($element);
        $this->cacheByString($element);
        return $this;
    }


    /*******************************************
     * FIND/GET BY STRING
     *******************************************/

    /**
     * @param string $string
     * @param int|null $siteId
     * @param string|null $toScenario
     * @return ElementInterface|null
     */
    public function findByString(string $string, int $siteId = null, string $toScenario = null)
    {
        $siteId = SiteHelper::ensureSiteId($siteId);

        if (!$element = $this->findCacheByString($string, $siteId)) {
            if (!$element = $this->freshFindByString($string, $siteId)) {
                $this->cacheByString[$siteId][$string] = null;
                return null;
            }

            $this->addToCache($element);
        }

        return $this->applyScenario($element, $toScenario);
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return ElementInterface|null
     * @throws ElementNotFoundException
     */
    public function getByString(string $string, string $toScenario = null): ElementInterface
    {
        if (!$element = $this->findByString($string, $toScenario)) {
            $this->notFoundByStringException($string);
        }

        return $element;
    }

    /**
     * @param string $string
     * @param int|null $siteId
     * @param string $toScenario
     * @return ElementInterface|null
     */
    public function freshFindByString(string $string, int $siteId = null, string $toScenario = null)
    {
        /** @var ElementQuery $query */
        $query = $this->getQuery();
        $query->{$this->stringProperty()} = $string;
        $query->siteId = $siteId;
        $query->status = null;
        $query->enabledForSite = false;

        if (!$element = $query->one()) {
            return null;
        }

        return $this->applyScenario($element, $toScenario);
    }

    /**
     * @param string $string
     * @param string|null $toScenario
     * @return ElementInterface
     * @throws ElementNotFoundException
     */
    public function freshGetByString(string $string, string $toScenario = null): ElementInterface
    {
        if (!$element = $this->freshFindByString($string, $toScenario)) {
            $this->notFoundByStringException($string);
        }

        return $element;
    }


    /*******************************************
     * CACHE BY STRING
     *******************************************/

    /**
     * Find an existing cache by ID
     *
     * @param string $string
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function findCacheByString(string $string, int $siteId = null)
    {
        $siteId = SiteHelper::ensureSiteId($siteId);

        if ($this->isCachedByString($string, $siteId)) {
            return $this->cacheByString[$siteId][$string];
        }

        return null;
    }

    /**
     * Identify whether in cached by ID
     *
     * @param string $string
     * @param int|null $siteId
     * @return bool
     */
    protected function isCachedByString(string $string, int $siteId = null): bool
    {
        $siteId = SiteHelper::ensureSiteId($siteId);

        if (!array_key_exists($siteId, $this->cacheByString)) {
            $this->cacheByString[$siteId] = [];
        }

        return array_key_exists($string, $this->cacheByString[$siteId]);
    }

    /**
     * @param ElementInterface $element
     * @return $this
     */
    protected function cacheByString(ElementInterface $element)
    {
        /** @var Element $element */
        $stringValue = $this->stringValue($element);

        if (null === $stringValue) {
            return $this;
        }

        $siteId = SiteHelper::ensureSiteId($element->siteId);

        // Check if already in cache
        if (!$this->isCachedByString($stringValue, $siteId)) {
            $this->cacheByString[$siteId][$stringValue] = $element;
        }

        return $this;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @param string|null $string
     * @throws ElementNotFoundException
     */
    protected function notFoundByStringException(string $string = null)
    {
        throw new ElementNotFoundException(
            sprintf(
                'Element does not exist with the string "%s".',
                (string)$string
            )
        );
    }
}
