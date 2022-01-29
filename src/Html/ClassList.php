<?php

declare(strict_types=1);

namespace ByTIC\Html\Html;

use InvalidArgumentException;

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

    /**
     * @param $classes
     * @return ClassList
     */
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

        throw new InvalidArgumentException("Classes should be string or array");
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
     * @param $class
     * @return $this
     */
    public function remove($class): self
    {
        $key = array_search($class, $this->classes);
        if ($key !== false) {
            unset($this->classes[$key]);
        }
        return $this;
    }

    /**
     * @param $class
     * @return bool
     */
    public function contains($class): bool
    {
        return in_array($class, $this->classes);
    }

    /**
     * @param $class
     * @return void
     */
    public function toggle($class): self
    {
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
