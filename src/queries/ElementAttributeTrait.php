<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use craft\base\ElementInterface;
use craft\db\Query;
use craft\db\QueryAbortedException;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementAttributeTrait
{
    /**
     * The element(s) that the resulting organizations’ elements must have.
     *
     * @var string|string[]|int|int[]|ElementInterface|ElementInterface[]|null
     */
    public $element;

    /**
     * @param string|string[]|int|int[]|ElementInterface|ElementInterface[]|null $value
     * @return static The query object
     */
    public function setElement($value)
    {
        $this->element = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|ElementInterface|ElementInterface[]|null $value
     * @return static The query object
     */
    public function element($value)
    {
        return $this->setElement($value);
    }

    /**
     * @param string|string[]|int|int[]|ElementInterface|ElementInterface[]|null $value
     * @return static The query object
     */
    public function setElementId($value)
    {
        return $this->setElement($value);
    }

    /**
     * @param string|string[]|int|int[]|ElementInterface|ElementInterface[]|null $value
     * @return static The query object
     */
    public function elementId($value)
    {
        return $this->setElement($value);
    }

    /**
     * @param $value
     * @return int
     * @throws QueryAbortedException
     */
    protected function parseElementValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $uri) {
                $value = (new Query())
                    ->select(['id'])
                    ->from(['{{%elements_sites}} elements_sites'])
                    ->where(['uri' => $uri])
                    ->scalar();
                return empty($value) ? false : $value;
            }
        );

        if ($return !== null && empty($return)) {
            throw new QueryAbortedException();
        }

        return $return;
    }
}
