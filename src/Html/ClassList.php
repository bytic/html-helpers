<?php

namespace ByTIC\Html\Html;

/**
 * Class ClassList
 * @package ByTIC\Html\Html
 */
class ClassList
{
    protected $classes = [];

    /**
     * ClassList constructor.
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    public static function create($classes): ClassList
    {
        if ($classes instanceof static) {
            return $classes;
        }
        if (is_string($classes)) {
            $classes = explode(' ', $classes);
        }
        if (is_array($classes)) {
            return new static($classes);
        }

        throw new \InvalidArgumentException("Classes should be string or array");
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @param array $classes
     */
    public function setClasses(array $classes): void
    {
        $this->classes = $classes;
    }

    /**
     * @param string ...$args
     *
     * @return  static
     */
    public function add(string ...$args): self
    {
        $classes = $this->getClasses();

        $this->classes = array_values(array_unique(array_merge($classes, $args)));

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(' ', $this->classes);
    }
}