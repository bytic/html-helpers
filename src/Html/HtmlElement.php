<?php

declare(strict_types=1);

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
     * @return string
     */
    public function toString($forcePair = false): string
    {
        return HtmlBuilder::create($this->name, $this->content, $this->attribs->toArray(), $forcePair);
    }

    /**
     * addClass
     *
     * @param string|callable $class
     *
     * @return  static
     */
    public function addClass($class): self
    {
        $this->attribs->addClass($class);

        return $this;
    }

    /**
     * removeClass
     *
     * @param string|callable $class
     *
     * @return  static
     */
    public function removeClass($class): self
    {
        $this->attribs->removeClass($class);

        return $this;
    }

    public function toggleClass(string $class, ?bool $force = null): self
    {
        $this->attribs->toggleClass($class, $force);

        return $this;
    }


    public function hasClass(string $class): bool
    {
        return $this->attribs->hasClass($class);
    }

    /**
     * @return ClassList|mixed
     */
    public function getClassList()
    {
        return $this->attribs->getClassList();
    }

    /**
     * data
     *
     * @param string $name
     * @param mixed $value
     *
     * @return  string|static
     */
    public function data(string $name, $value = null)
    {
        return $this->attribs->data($name, $value);
    }
}
