<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\services\objects;

use flipbox\craft\ember\exceptions\ObjectNotFoundException;
use yii\base\BaseObject;

/**
 * Similar to the inherited Accessor, you can also specific a unique string (typically a handle) to
 * retrieve an object.
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 *
 * @method BaseObject parentFind($identifier)
 */
trait ObjectAccessorByStringTrait
{
    use ObjectAccessorTrait {
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
     * @return BaseObject|null
     */
    public function find($identifier)
    {
        if (!is_numeric($identifier) && is_string($identifier)) {
            return $this->findByString($identifier);
        }

        return $this->parentFind($identifier);
    }

    /*******************************************
     * FIND STRING
     *******************************************/

    /**
     * @param string $string
     * @return BaseObject|null
     */
    public function findByString(string $string)
    {
        return $this->findByCondition(
            [self::stringProperty() => $string]
        );
    }

    /**
     * @param string $string
     * @throws ObjectNotFoundException
     * @return BaseObject|null
     */
    public function getByString(string $string)
    {
        if (null === ($record = $this->findByString($string))) {
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
        throw new ObjectNotFoundException(
            sprintf(
                'Object does not exist with the string "%s".',
                (string)$string
            )
        );
    }
}
