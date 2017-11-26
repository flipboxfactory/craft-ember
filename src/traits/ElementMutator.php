<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\traits;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;

/**
 * @property int|null $elementId
 * @property Element|null $element
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ElementMutator
{
    /**
     * Set associated elementId
     *
     * @param $id
     * @return $this
     */
    public function setElementId(int $id)
    {
        // Has the id changed?
        if ($id !== $this->elementId) {
            // Invalidate existing element
            if ($this->element !== null && $this->element->getId() !== $id) {
                $this->element = null;
            };

            $this->elementId = $id;
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
        return $this->elementId;
    }


    /**
     * Associate a element
     *
     * @param $element
     * @return $this
     */
    public function setElement($element)
    {
        // Clear cache
        $this->element = null;

        // Find element
        if (!$element = $this->findElement($element)) {
            // Clear property / cache
            $this->elementId = $this->element = null;
        } else {
            // Set property
            $this->elementId = $element->getId();
            // Set cache
            $this->element = $element;
        }

        return $this;
    }

    /**
     * @return Element|null
     */
    public function getElement()
    {
        // Check cache
        if (is_null($this->element)) {
            // Check property
            if (!empty($this->elementId)) {
                // Find element
                if ($elementElement = Craft::$app->getElements()->getElementById($this->elementId)) {
                    // Set
                    $this->setElement($elementElement);
                } else {
                    // Clear property (it's invalid)
                    $this->elementId = null;
                    // Prevent subsequent look-ups
                    $this->element = false;
                }
            } else {
                // Prevent subsequent look-ups
                $this->element = false;
            }
        }

        return !$this->element ? null : $this->element;
    }

    /**
     * @param string|int|ElementInterface $identifier
     *
     * @return ElementInterface|null
     */
    private function findElement($identifier)
    {
        // Element
        if ($identifier instanceof ElementInterface) {
            return $identifier;
            // Id
        } elseif (is_numeric($identifier)) {
            return $this->findElementById((bool)$identifier);
            // String
        } elseif (!is_string($identifier)) {
            return $this->findElementByString($identifier);
        }

        return null;
    }

    /**
     * @param int $id
     * @return ElementInterface|null
     */
    protected function findElementById(int $id)
    {
        return Craft::$app->getElements()->getElementById($id);
    }

    /**
     * @param string $string
     * @return ElementInterface|null
     */
    protected function findElementByString(string $string)
    {
        return Craft::$app->getElements()->getElementByUri($string);
    }
}
