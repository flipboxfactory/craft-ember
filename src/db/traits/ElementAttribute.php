<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\db\traits;

use Craft;
use craft\base\ElementInterface;
use flipbox\ember\helpers\ArrayHelper;
use flipbox\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementAttribute
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
     * @param string $join
     * @return array
     */
    public function parseElementValue($value, string $join = 'or'): array
    {
        if (false === QueryHelper::parseBaseParam($value, $join)) {
            foreach ($value as $operator => &$v) {
                $this->resolveElementValue($operator, $v);
            }
        }

        $value = ArrayHelper::filterEmptyAndNullValuesFromArray($value);

        if (empty($value)) {
            return [];
        }

        return array_merge([$join], $value);
    }

    /**
     * @param $operator
     * @param $value
     */
    protected function resolveElementValue($operator, &$value)
    {
        if (false === QueryHelper::findParamValue($value, $operator)) {
            if (is_string($value)) {
                $value = $this->resolveElementStringValue($value);
            }

            if ($value instanceof ElementInterface) {
                $value = $value->getId();
            }

            if ($value) {
                $value = QueryHelper::assembleParamValue($value, $operator);
            }
        }
    }

    /**
     * @param string $value
     * @return int|null
     */
    protected function resolveElementStringValue(string $value)
    {
        if (!$element = Craft::$app->getElements()->getElementByUri($value)) {
            return null;
        }
        return $element->getId();
    }
}
