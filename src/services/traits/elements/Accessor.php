<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\elements;

use craft\base\ElementInterface;
use craft\elements\db\ElementQueryInterface;
use craft\errors\ElementNotFoundException;
use craft\helpers\ArrayHelper;
use flipbox\ember\helpers\ObjectHelper;
use flipbox\ember\helpers\QueryHelper;
use flipbox\ember\helpers\SiteHelper;
use flipbox\ember\services\traits\queries\BaseAccessor;
use yii\base\BaseObject;
use yii\db\QueryInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method BaseObject parentQueryOne(QueryInterface $query)
 * @method BaseObject[] parentQueryAll(QueryInterface $query)
 */
trait Accessor
{
    use BaseAccessor;

    /*******************************************
     * OBJECT CLASSES
     *******************************************/

    /**
     * @return string|null
     */
    abstract public static function elementClass();

    /*******************************************
     * CREATE
     *******************************************/

    /**
     * @param array $config
     * @return ElementInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function create($config = []): ElementInterface
    {
        if ($config instanceof ElementInterface) {
            return $config;
        }

        /** @var ElementInterface $element */
        $element = ObjectHelper::create(
            $this->prepareConfig($config),
            ElementInterface::class
        );

        return $element;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareConfig($config = []): array
    {
        if (!is_array($config)) {
            $config = ArrayHelper::toArray($config, [], false);
        }

        // Auto-set the class
        $class = static::elementClass();
        if ($class !== null) {
            $config['class'] = $class;
        }

        return $config;
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
    public function getQuery($criteria = []): ElementQueryInterface
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
     * FIND / GET
     *******************************************/

    /**
     * @param int|null $siteId
     * @return ElementInterface[]
     */
    public function findAll(int $siteId = null)
    {
        $config = [];
        if ($siteId !== null) {
            $config['siteId'] = $siteId;
        }
        return $this->getQuery($config)->all();
    }

    /**
     * @param $identifier
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function find($identifier, int $siteId = null)
    {
        if ($identifier instanceof ElementInterface) {
            return $identifier;
        }

        return $this->findByQuery($this->getQuery([
            'id' => $identifier,
            'siteId' => SiteHelper::ensureSiteId($siteId)
        ]));
    }

    /**
     * @param $identifier
     * @param int $siteId
     * @return ElementInterface
     * @throws ElementNotFoundException
     */
    public function get($identifier, int $siteId = null): ElementInterface
    {
        if (!$object = $this->find($identifier, $siteId)) {
            $this->notFoundException();
        }

        return $object;
    }

    /*******************************************
     * EXCEPTIONS
     *******************************************/

    /**
     * @param int|null $id
     * @throws ElementNotFoundException
     */
    protected function notFoundByIdException(int $id = null)
    {
        throw new ElementNotFoundException(
            sprintf(
                'Element does not exist given the id "%s".',
                (string)$id
            )
        );
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function notFoundException()
    {
        throw new ElementNotFoundException(
            sprintf(
                "Element not found."
            )
        );
    }
}
