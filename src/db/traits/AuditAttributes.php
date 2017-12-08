<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\db\traits;

use craft\helpers\Db;
use yii\db\Expression;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait AuditAttributes
{
    /**
     * @var string|string[]|null The element UID(s). Prefix UIDs with "not " to exclude them.
     */
    public $uid;

    /**
     * @var mixed When the resulting elements must have been created.
     */
    public $dateCreated;

    /**
     * @var mixed When the resulting elements must have been last updated.
     */
    public $dateUpdated;

    /**
     * Adds an additional WHERE condition to the existing one.
     * The new condition and the existing one will be joined using the `AND` operator.
     * @param string|array|Expression $condition the new WHERE condition. Please refer to [[where()]]
     * on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return $this the query object itself
     * @see where()
     * @see orWhere()
     */
    abstract public function andWhere($condition, $params = []);

    /**
     * @inheritdoc
     * return static
     */
    public function uid($value)
    {
        $this->uid = $value;

        return $this;
    }

    /**
     * @inheritdoc
     * return static
     */
    public function dateCreated($value)
    {
        $this->dateCreated = $value;

        return $this;
    }

    /**
     * @inheritdoc
     * return static
     */
    public function dateUpdated($value)
    {
        $this->dateUpdated = $value;

        return $this;
    }

    /**
     *
     */
    protected function applyAuditAttributeConditions()
    {
        if ($this->uid !== null) {
            $this->andWhere(Db::parseParam('uid', $this->uid));
        }

        if ($this->dateCreated !== null) {
            $this->andWhere(Db::parseDateParam('dateCreated', $this->dateCreated));
        }

        if ($this->dateUpdated !== null) {
            $this->andWhere(Db::parseDateParam('dateUpdated', $this->dateUpdated));
        }
    }
}
