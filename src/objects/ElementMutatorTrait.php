<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/craft-ember/blob/master/LICENSE
 * @link       https://github.com/flipboxfactory/craft-ember/
 */

namespace flipbox\craft\ember\objects;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;

/**
 * This trait accepts both an ElementInterface or and Element Id and ensures that the both
 * the ElementInterface and the Id are in sync. If one changes (and does not match the other) it
 * resolves (removes / updates) the other.
 *
 * In addition, this trait is primarily useful when a new Element is set and saved; the Element
 * Id can be retrieved without needing to explicitly set the newly created Id.
 *
 * @property Element|ElementInterface|null $element
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait ElementMutatorTrait
{
    /**
     * @var Element|null
     */
    private $element;

    /**
     * Internally set the Element Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetElementId(int $id = null);

    /**
     * Internally get the Element Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetElementId();

    /**
     * @return bool
     */
    public function isElementSet(): bool
    {
        return null !== $this->element;
    }

    /**
     * Set associated elementId
     *
     * @param $id
     * @return $this
     */
    public function setElementId(int $id = null)
    {
        $this->internalSetElementId($id);

        if (null !== $this->element && $id !== $this->element->id) {
            $this->element = null;
        }

        return $this;
    }

    /**
     * Get associated elementId
     *
     * @return int|null
     */
    public function getElementId()
    {
        if (null === $this->internalGetElementId() && null !== $this->element) {
            $this->setElementId($this->element->id);
        }

        return $this->internalGetElementId();
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
        $this->internalSetElementId(null);

        if (null !== ($element = $this->verifyElement($element))) {
            $this->element = $element;
            $this->internalSetElementId($element->id);
        }

        return $this;
    }

    /**
     * @return ElementInterface|null
     */
    public function getElement()
    {
        if ($this->element === null) {
            $element = $this->resolveElement();
            $this->setElement($element);
            return $element;
        }

        $elementId = $this->internalGetElementId();
        if ($elementId !== null && $elementId !== $this->element->id) {
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
        if ($element = $this->resolveElementFromId()) {
            return $element;
        }

        return null;
    }

    /**
     * @return ElementInterface|null
     */
    private function resolveElementFromId()
    {
        if (null === ($elementId = $this->internalGetElementId())) {
            return null;
        }

        return Craft::$app->getElements()->getElementById($elementId);
    }

    /**
     * @param mixed $element
     * @return ElementInterface|Element|null
     */
    protected function verifyElement($element = null)
    {
        if (null === $element) {
            return null;
        }

        if ($element instanceof ElementInterface) {
            return $element;
        }

        if (is_numeric($element)) {
            return Craft::$app->getElements()->getElementById($element);
        }

        if (is_string($element)) {
            return Craft::$app->getElements()->getElementByUri($element);
        }

        return null;
    }
}
