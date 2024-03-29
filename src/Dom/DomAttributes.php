<?php

declare(strict_types=1);

namespace ByTIC\Html\Dom;

use ByTIC\Html\Html\ClassList;
use ByTIC\Html\Html\HtmlBuilder;

/**
 *
 */
class DomAttributes implements \IteratorAggregate, \ArrayAccess, \Countable
{

    /** @var array */
    protected $attributes = [];

    /**
     * @param array|self $attributes
     */
    public function __construct($attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function getAttribute($key, $default = null)
    {
        if (empty($this->attributes[$key])) {
            return $default;
        }

        return $this->attributes[$key];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            if (is_int($attribute)) {
                $attribute = $value;
                $value = '';
            }
            $this->setAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * @param string $attribute
     * @param string|mixed $value
     *
     * @return $this
     */
    public function setAttribute(string $attribute, $value = null): self
    {
        if ($attribute === 'class') {
            $this->addClass($value);

            return $this;
        }
        $this->offsetSet($attribute, $value);

        return $this;
    }

    /**
     * data
     *
     * @param string $name
     * @param mixed $value
     *
     * @return  string|static
     *
     * @since  3.5.3
     */
    public function data(string $name, $value = null)
    {
        if ($value === null) {
            return $this->getAttribute('data-' . $name);
        }

        return $this->setAttribute('data-' . $name, $value);
    }

    public function hasAttribute(string $attribute): bool
    {
        return $this->offsetExists($attribute);
    }

    /**
     * Alias for mergeAttributes().
     *
     * @param string $attribute
     * @param string|array $value
     *
     * @return $this
     */
    public function merge($attribute, $value): self
    {
        return $this->mergeAttribute($attribute, $value);
    }

    public function mergeAttributes(array $attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            if (is_int($attribute)) {
                $attribute = $value;
                $value = '';
            }
            $this->mergeAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * Add a value or values to an attribute, leaving existing values in place.
     *
     * @param string $attribute
     * @param string|array $value
     *
     * @return $this
     */
    public function mergeAttribute(string $attribute, $value)
    {
        $values = $this->offsetGet($attribute);

        if ($attribute == 'class') {
            return $this->addClass($value);
        }
        if (empty($values)) {
            $values = [];
        } else {
            $values = explode(' ', $values);
        }

        $values[] = $value;
        $values = array_filter($values);
        $values = array_unique($values);

        $this->offsetSet($attribute, implode(' ', $values));

        return $this;
    }

    /**
     * Remove an attribute from the list.
     *
     * @return $this
     */
    public function removeAttribute(string $attribute): self
    {
        $this->offsetUnset($attribute);

        return $this;
    }

    /**
     * addClass
     *
     * @param string|callable $class
     *
     * @return  static
     *
     * @since  3.5.3
     */
    public function addClass($class): self
    {
        if (is_array($class)) {
            $classes = $class;
        } elseif ($class instanceof ClassList) {
            $classes = $class->getClasses();
        } else {
            $classes = array_filter(explode(' ', $class), 'strlen');
        }

        $this->getClassList()->add(...$classes);

        return $this;
    }

    /**
     * removeClass
     *
     * @param string|callable $class
     *
     * @return  static
     *
     * @since  3.5.3
     */
    public function removeClass($class): self
    {
//        $class = Str::toString($class);

        $classes = array_filter(explode(' ', $class), 'strlen');

        $this->getClassList()->remove(...$classes);

        return $this;
    }


    public function toggleClass(string $class, ?bool $force = null): self
    {
        $this->getClassList()->toggle($class, $force);

        return $this;
    }


    public function hasClass(string $class): bool
    {
        return $this->getClassList()->contains($class);
    }

    /**
     * @return ClassList
     */
    public function getClassList(): ClassList
    {
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = ClassList::create([]);
        }
        if (!($this->attributes['class'] instanceof ClassList)) {
            $this->attributes['class'] = ClassList::create($this->attributes['class']);
        }
        return $this->attributes['class'];
    }

    public function isEmpty(): bool
    {
        return empty($this->attributes) && empty($this->classes);
    }


    /**
     * Get the value of attributes at the specified offset.
     *
     * @param string $offset
     *
     * @return string|ClassList
     */
    public function offsetGet($offset): mixed
    {
        if (isset($this->attributes[$offset])) {
            return $this->attributes[$offset];
        }
        return null;
    }

    /**
     * Set the value of attributes at the specified offset.
     *
     * @param string $offset
     * @param string|mixed $value
     *
     * @return void;
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Retrieve an external iterator
     *
     * @return  \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->attributes);
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;
        if ($this->getClassList()->count()) {
            $attributes['class'] = $this->getClassList()->getClasses();
        } else {
            unset($attributes['class']);
        }


        return $attributes;
    }

    /**
     * Return a new instance with only the specified key(s).
     *
     * @param array|string $keys
     *
     * @return $this
     */
    public function only($keys): self
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        $instance = new self([]);
        foreach ($keys as $key) {
            $instance->setAttribute($key, $this->offsetGet($key));
        }

        return $instance;
    }

    /**
     * Return a new instance without the specified key(s).
     *
     * @param array|string $keys
     *
     * @return $this
     */
    public function without($keys): self
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        $instance = new self($this->attributes);

        foreach ($keys as $key) {
            $instance->removeAttribute($key);
        }

        return $instance;
    }

    public function toString(): string
    {
        return HtmlBuilder::buildAttributes($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->attributes);
    }
}