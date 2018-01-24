<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\ArrayHelper;
use flipbox\ember\exceptions\ElementNotFoundException;
use flipbox\ember\helpers\ObjectHelper;
use flipbox\ember\helpers\QueryHelper;
use flipbox\ember\helpers\SiteHelper;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementAccessor
{
    /**
     * @var [ElementInterface[]]
     */
    protected $cacheById = [];

    /*******************************************
     * OBJECT CLASSES
     *******************************************/

    /**
     * @return string
     */
    abstract public static function elementClass(): string;

    /**
     * @return string
     */
    public static function elementClassInstance(): string
    {
        return ElementInterface::class;
    }

    /*******************************************
     * CREATE
     *******************************************/

    /**
     * @param array $config
     * @param string|null $toScenario
     * @throws InvalidConfigException
     * @return ElementInterface
     */
    public function create($config = [], string $toScenario = null): ElementInterface
    {
        // Treat records as known data and set via config
        if ($config instanceof ElementInterface) {
            return $this->applyScenario($config, $toScenario);
        }

        // Force Array
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        // Auto-set the class
        if ($class = static::elementClass()) {
            $config['class'] = $class;
        }

        /** @var ElementInterface $element */
        $element = ObjectHelper::create(
            $config,
            static::elementClassInstance()
        );

        return $this->applyScenario($element, $toScenario);
    }

    /*******************************************
     * QUERY
     *******************************************/

    /**
     * Get query
     *
     * @param $criteria
     * @return ElementQueryInterface
     */
    public function getQuery($criteria = [])
    {
        /** @var ElementInterface $elementClass */
        $elementClass = static::elementClass();

        /** @var ElementQueryInterface $query */
        $query = $elementClass::find();

        // Configure it
        QueryHelper::configure(
            $query,
            $criteria
        );

        return $query;
    }

    /*******************************************
     * FIND/GET
     *******************************************/

    /**
     * @param $identifier
     * @param int $siteId
     * @param string $toScenario
     * @return ElementInterface|null
     */
    public function find($identifier, int $siteId = null, string $toScenario = null)
    {
        if ($identifier instanceof ElementInterface) {
            $this->addToCache($identifier);
            return $this->applyScenario($identifier, $toScenario);
        } elseif (is_numeric($identifier)) {
            return $this->findById($identifier, $siteId);
        } elseif (is_array($identifier)) {
            $element = $this->getQuery($identifier)
                ->siteId($siteId)
                ->one();
            return $this->applyScenario($element, $toScenario);
        }

        return null;
    }

    /**
     * @param $identifier
     * @param int $siteId
     * @param string $toScenario
     * @return ElementInterface
     * @throws ElementNotFoundException
     */
    public function get($identifier, int $siteId = null, string $toScenario = null): ElementInterface
    {
        if (!$object = $this->find($identifier, $siteId, $toScenario)) {
            $this->notFoundException();
        }

        return $object;
    }


    /*******************************************
     * FIND/GET BY ID
     *******************************************/

    /**
     * @param int $id
     * @param int|null $siteId
     * @param string $toScenario
     * @return ElementInterface|null
     */
    public function findById(int $id, int $siteId = null, string $toScenario = null)
    {
        if (!$element = $this->findCacheById($id, $siteId)) {
            if (!$element = $this->freshFindById($id, $siteId)) {
                $this->cacheById[$id] = null;
                return null;
            }

            $this->addToCache($element);
        }

        return $this->applyScenario($element, $toScenario);
    }


    /*******************************************
     * FRESH FIND
     *******************************************/

    /**
     * @param int $id
     * @param int|null $siteId
     * @param string $toScenario
     * @return ElementInterface|null
     */
    public function freshFindById(int $id, int $siteId = null, string $toScenario = null)
    {
        /** @var ElementQuery $query */
        $query = $this->getQuery();
        $query->id = $id;
        $query->siteId = $siteId;
        $query->status = null;
        $query->enabledForSite = false;

        if (!$element = $query->one()) {
            return null;
        }

        return $this->applyScenario($element, $toScenario);
    }

    /**
     * @param $id
     * @param int|null $siteId
     * @param string $toScenario
     * @return ElementInterface
     * @throws ElementNotFoundException
     */
    public function freshGetById($id, int $siteId = null, string $toScenario = null)
    {
        if (!$element = $this->freshFindById($id, $siteId)) {
            $this->notFoundByIdException($id);
        }

        return $element;
    }

    /*******************************************
     * CACHE
     *******************************************/

    /**
     * @param $identifier
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function findCache($identifier, int $siteId = null)
    {
        if (is_numeric($identifier)) {
            return $this->findCacheById($identifier, $siteId);
        }

        return null;
    }

    /**
     * @param ElementInterface $element
     * @return static
     */
    public function addToCache(ElementInterface $element)
    {
        $this->cacheById($element);
        return $this;
    }


    /*******************************************
     * CACHE BY ID
     *******************************************/

    /**
     * Find an existing cache by ID
     *
     * @param int $id
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function findCacheById(int $id, int $siteId = null)
    {
        $siteId = SiteHelper::resolveSiteId($siteId);

        if ($this->isCachedById($id, $siteId)) {
            return $this->cacheById[$siteId][$id];
        }

        return null;
    }

    /**
     * Identify whether in cached by ID
     *
     * @param int $id
     * @param int|null $siteId
     * @return bool
     */
    protected function isCachedById(int $id, int $siteId = null): bool
    {
        $siteId = SiteHelper::resolveSiteId($siteId);

        if (!array_key_exists($siteId, $this->cacheById)) {
            $this->cacheById[$siteId] = [];
        }

        return array_key_exists($id, $this->cacheById[$siteId]);
    }

    /**
     * @param ElementInterface $element
     * @return $this
     */
    protected function cacheById(ElementInterface $element)
    {
        /** @var Element $element */
        $id = $element->getId();
        $siteId = $element->siteId;

        // todo - ensure siteId is set?

        // Check if already in cache
        if (!$this->isCachedById($id, $siteId)) {
            $this->cacheById[$siteId][$id] = $element;
        }

        return $this;
    }


    /*******************************************
     * UTILITIES
     *******************************************/

    /**
     * @param ElementInterface|Element $element
     * @param string|null $toScenario
     * @return ElementInterface
     */
    protected function applyScenario(ElementInterface $element, string $toScenario = null): ElementInterface
    {
        if (null !== $toScenario) {
            $element->setScenario($toScenario);
        }

        return $element;
    }


    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @throws ElementNotFoundException
     */
    protected function notFoundException()
    {
        throw new ElementNotFoundException(
            sprintf(
                "Element does not exist."
            )
        );
    }

    /**
     * @param int|null $id
     * @throws ElementNotFoundException
     */
    protected function notFoundByIdException(int $id = null)
    {
        throw new ElementNotFoundException(
            sprintf(
                'Element does not exist with the id "%s".',
                (string)$id
            )
        );
    }
}
