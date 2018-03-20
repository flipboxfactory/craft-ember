<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\services\traits\records;

use flipbox\ember\exceptions\RecordNotFoundException;
use yii\db\ActiveRecord as Record;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method Record parentFind($identifier)
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
     * @return Record|null
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
     * @return Record|null
     */
    public function findByString(string $string)
    {
        return $this->findByCondition(
            [self::stringProperty() => $string]
        );
    }

    /**
     * @param string $string
     * @throws RecordNotFoundException
     * @return Record|null
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
     * @throws RecordNotFoundException
     */
    protected function notFoundByStringException(string $string = null)
    {
        throw new RecordNotFoundException(
            sprintf(
                'Record does not exist with the string "%s".',
                (string)$string
            )
        );
    }
}
