<?php

namespace ByTIC\Html\Html;

use ByTIC\Html\Dom\DomElement;

/**
 * Class HtmlElement
 * @package ByTIC\Html\Html
 */
class HtmlElement extends DomElement
{
    /**
     * toString
     *
     * @param boolean $forcePair
     *
     * @return  string
     */
    public function toString($forcePair = false)
    {
        return HtmlBuilder::create($this->name, $this->content, $this->attribs, $forcePair);
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
        $classes = array_filter(explode(' ', $class), 'strlen');

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


    public function hasClass(string $class)
    {
        return $this->getClassList()->contains($class);
    }

    /**
     * @return ClassList|mixed
     */
    public function getClassList()
    {
        if (!isset($this->attribs['class'])) {
            $this->attribs['class'] = ClassList::create([]);
        }
        if (!($this->attribs['class'] instanceof ClassList)) {
            $this->attribs['class'] = ClassList::create($this->attribs['class']);
        }
        return $this->attribs['class'];
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

    /**
     * __get
     *
     * @param string $name
     *
     * @return  mixed
     *
     * @since  3.5.3
     */
    public function __get($name)
    {
        if ($name === 'classList') {
            return new DOMTokenList($this);
        }

        if ($name === 'dataset') {
            return new DOMStringMap($this);
        }

        return $this->{$name};
    }
}
