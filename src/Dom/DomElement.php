<?php

declare(strict_types=1);

namespace ByTIC\Html\Dom;

/**
 * Class DomElement
 * @package ByTIC\Html\Dom
 */
class DomElement implements \ArrayAccess
{
    /**
     * Element tag name.
     *
     * @var  string
     */
    protected $name;

    /**
     * Element attributes.
     *
     * @var  DomAttributes
     */
    protected $attribs;

    /**
     * Element content.
     *
     * @var  mixed
     */
    protected $content;

    /**
     * Constructor
     *
     * @param string $name Element tag name.
     * @param mixed $content Element content.
     * @param array $attribs Element attributes.
     */
    public function __construct(string $name, $content = null, $attribs = [])
    {
        if (is_array($content)) {
            $content = new DomElements($content);
        }

        $this->name = $name;
        $this->attribs = new DomAttributes($attribs);
        $this->content = $content;
    }

    /**
     * toString
     *
     * @param boolean $forcePair
     *
     * @return  string
     */
    public function toString($forcePair = false)
    {
        return DomBuilder::create($this->name, $this->content, $this->attribs->toArray(), $forcePair);
    }

    /**
     * Alias of toString()
     *
     * @param boolean $forcePair
     *
     * @return  string
     */
    public function render($forcePair = false)
    {
        return $this->toString($forcePair);
    }

    /**
     * Convert this object to string.
     *
     * @return  string
     */
    public function __toString()
    {
        try {
            return $this->toString();
        } catch (\Throwable $exception) {
            return (string)$exception;
        }
    }

    /**
     * Get content.
     *
     * @return  mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param mixed $content Element content.
     *
     * @return  static  Return self to support chaining.
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @param string $name Attribute name.
     * @param mixed $default Default value.
     *
     * @return  string The attribute value.
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attribs->getAttribute($name, $default);
    }

    /**
     * Set attribute value.
     *
     * @param string $name Attribute name.
     * @param string $value The value to set into attribute.
     *
     * @return  static  Return self to support chaining.
     */
    public function setAttribute($name, $value)
    {
        $this->attribs->setAttribute($name, $value);

        return $this;
    }

    /**
     * hasAttribute
     *
     * @param string $name
     *
     * @return  bool
     *
     * @since  3.5.3
     */
    public function hasAttribute($name)
    {
        return $this->attribs->hasAttribute($name);
    }

    /**
     * removeAttribute
     *
     * @param string $name
     *
     * @return  $this
     */
    public function removeAttribute($name)
    {
        $this->attribs->removeAttribute($name);

        return $this;
    }

    /**
     * Get all attributes.
     *
     * @return array|DomAttributes
     */
    public function getAttributes()
    {
        return $this->attribs;
    }

    /**
     * Set all attributes.
     *
     * @param array $attribs All attributes.
     *
     * @return  static  Return self to support chaining.
     */
    public function setAttributes($attribs)
    {
        $this->attribs->setAttributes($attribs);

        return $this;
    }

    /**
     * Get element tag name.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set element tag name.
     *
     * @param string $name Set element tag name.
     *
     * @return  static  Return self to support chaining.
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean True on success or false on failure.
     *                 The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset): bool
    {
        return $this->attribs->offsetExists($offset);
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
        return $this->attribs->offsetGet($offset);
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->attribs->offsetSet($offset, $value);
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
        $this->attribs->offsetUnset($offset);
    }
}
