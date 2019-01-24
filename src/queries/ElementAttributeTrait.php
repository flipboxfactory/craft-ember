<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\queries;

use Craft;
use craft\base\ElementInterface;
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
     * @return array|string
     */
    protected function parseElementValue($value)
    {
        return QueryHelper::prepareParam(
            $value,
            function(string $uri) {
                return Craft::$app->getElements()->getElementByUri($uri);
            }
        );
    }
}
