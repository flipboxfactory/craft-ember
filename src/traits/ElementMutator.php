<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;

/**
 * @property int|null $elementId
 * @property Element|ElementInterface|null $element
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementMutator
{
    /**
     * @var Element|null
     */
    private $element;

    /**
     * Set associated elementId
     *
     * @param $id
     * @return $this
     */
    public function setElementId(int $id)
    {
        $this->elementId = $id;
        return $this;
    }

    /**
     * Get associated elementId
     *
     * @return int|null
     */
    public function getElementId()
    {
        if (null === $this->elementId && null !== $this->element) {
            $this->elementId = $this->element->id;
        }

        return $this->elementId;
    }

    /**
     * Associate a element
     *
     * @param mixed $element
     * @return $this
     */
    public function setElement($element = null)
    {
        $this->element = null;

        if (!$element = $this->internalResolveElement($element)) {
            $this->element = $this->elementId = null;
        } else {
            /** @var Element $element */
            $this->elementId = $element->id;
            $this->element = $element;
        }

        return $this;
    }

    /**
     * @return ElementInterface|null
     */
    public function getElement()
    {
        /** @var Element $element */
        if ($this->element === null) {
            $element = $this->resolveElement();
            $this->setElement($element);
            return $element;
        }

        $elementId = $this->elementId;
        if ($elementId !== null &&
            $elementId !== $this->element->id
        ) {
            $this->element = null;
            return $this->getElement();
        }

        return $this->element;
    }

    /**
     * @return ElementInterface|null
     */
    protected function resolveElement()
    {
        if ($model = $this->resolveElementFromId()) {
            return $model;
        }

        return null;
    }

    /**
     * @return ElementInterface|null
     */
    private function resolveElementFromId()
    {
        if (null === $this->elementId) {
            return null;
        }

        return Craft::$app->getElements()->getElementById($this->elementId);
    }

    /**
     * @param mixed $element
     * @return ElementInterface|Element|null
     */
    protected function internalResolveElement($element = null)
    {
        if ($element instanceof ElementInterface) {
            return $element;
        }

        if (is_numeric($element)) {
            return Craft::$app->getElements()->getElementById($element);
        }

        if (is_string($element)) {
            return Craft::$app->getElements()->getElementByUri($element);
        }

        return Craft::$app->getElements()->createElement($element);
    }
}
