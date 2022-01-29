<?php

declare(strict_types=1);

namespace ByTIC\Html\Dom;

/**
 * Class DomBuilder
 * @package ByTIC\Html\Dom
 */
class DomBuilder
{
    /**
     * Create a html element.
     *
     * @param string $name      Element tag name.
     * @param mixed  $content   Element content.
     * @param array  $attribs   Element attributes.
     * @param bool   $forcePair Force pair it.
     *
     * @return  string Created element string.
     */
    public static function create($name, $content = '', $attribs = [], $forcePair = false)
    {
        $name = trim($name);

        $tag = '<' . $name;

        $tag .= static::buildAttributes($attribs);

        if ($content !== null) {
            $tag .= '>' . $content . '</' . $name . '>';
        } else {
            $tag .= $forcePair ? '></' . $name . '>' : ' />';
        }

        return $tag;
    }

    /**
     * buildAttributes
     *
     * @param array $attribs
     *
     * @return  string
     */
    public static function buildAttributes($attribs)
    {
        $string = '';

        foreach ((array) $attribs as $key => $value) {
            if ($value === true) {
                $string .= ' ' . $key;

                continue;
            }

            if ($value === null || $value === false) {
                continue;
            }

            $string .= ' ' . $key . '=' . static::quote($value);
        }

        return $string;
    }

    /**
     * quote
     *
     * @param string $value
     *
     * @return  string
     */
    public static function quote($value)
    {
        return '"' . $value . '"';
    }
}
