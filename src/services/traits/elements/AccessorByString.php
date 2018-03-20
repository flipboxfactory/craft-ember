<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\elements;

use craft\base\ElementInterface;
use craft\errors\ElementNotFoundException;
use flipbox\ember\exceptions\ObjectNotFoundException;
use flipbox\ember\helpers\SiteHelper;

/**
 * Similar to the inherited Accessor, you can also specific a unique string (typically a handle) to
 * retrieve an element.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method ElementInterface parentFind($identifier, int $siteId = null)
 */
trait AccessorByString
{
    use Accessor {
        find as parentFind;
    }

    /**
     * @return string
     */
    abstract protected static function stringProperty(): string;

    /*******************************************
     * OVERRIDE
     *******************************************/

    /**
     * @param $identifier
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function find($identifier, int $siteId = null)
    {
        if (!is_numeric($identifier) && is_string($identifier)) {
            return $this->findByString($identifier, $siteId);
        }

        return $this->parentFind($identifier, $siteId);
    }

    /*******************************************
     * FIND STRING
     *******************************************/

    /**
     * @param string $string
     * @param int|null $siteId
     * @return ElementInterface|null
     */
    public function findByString(string $string, int $siteId = null)
    {
        return $this->findByQuery($this->getQuery([
            static::stringProperty() => $string,
            'siteId' => SiteHelper::ensureSiteId($siteId)
        ]));
    }

    /**
     * @param string $string
     * @param int|null $siteId
     * @throws ElementNotFoundException
     * @return ElementInterface|null
     */
    public function getByString(string $string, int $siteId = null)
    {
        if (null === ($record = $this->findByString($string, $siteId))) {
            $this->notFoundByStringException($string);
        }

        return $record;
    }

    /**
     * @param string|null $string
     * @throws ObjectNotFoundException
     */
    protected function notFoundByStringException(string $string = null)
    {
        throw new ElementNotFoundException(
            sprintf(
                'Element does not exist given the value "%s".',
                (string)$string
            )
        );
    }
}
