<?php

declare(strict_types=1);

namespace ByTIC\Html\Dom;

use ByTIC\Html\Html\HtmlElement;

/**
 *
 */
class DomElements implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Property elements.
     *
     * @var  HtmlElement[]|mixed[]
     */
    protected $elements = [];

    /**
     * Property strict.
     *
     * @var boolean
     */
    protected $strict = false;

    /**
     * Class init.
     *
     * @param array|mixed $elements
     * @param boolean     $strict
     */
    public function __construct($elements = [], $strict = false)
    {
        if (is_object($elements)) {
            $elements = get_object_vars($elements);
        }

        $this->elements = (array) $elements;
        $this->strict = $strict;
    }

    /**
     * Convert all elements to string.
     *
     * @return  string
     */
    public function __toString()
    {
        $return = '';

        foreach ($this as $element) {
            $return .= (string) $element;
        }

        return $return;
    }

    /**
     * Retrieve an external iterator
     *
     * @return  \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Whether a offset exists
     *
     * @param  mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset): bool
    {
        return isset($this->elements[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset): mixed
    {
        if (!$this->strict && !$this->offsetExists($offset)) {
            return null;
        }

        return $this->elements[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === '' || $offset === null) {
            array_push($this->elements, $value);

            return;
        }

        $this->elements[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (!$this->strict && !$this->offsetExists($offset)) {
            return;
        }

        unset($this->elements[$offset]);
    }

    /**
     * Count elements of an object
     *
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Method to get property Strict
     *
     * @return  boolean
     */
    public function getStrict()
    {
        return $this->strict;
    }

    /**
     * Method to set property strict
     *
     * @param   boolean $strict
     *
     * @return  static  Return self to support chaining.
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * Method to get property Elements
     *
     * @return  \mixed[]|HtmlElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Method to set property elements
     *
     * @param   \mixed[]|HtmlElement[] $elements
     *
     * @return  static  Return self to support chaining.
     */
    public function setElements($elements)
    {
        $this->elements = $elements;

        return $this;
    }
}
