<?php

declare(strict_types=1);

namespace ByTIC\Html\Dom;

use Nip\Utility\Arr;
use Nip\Utility\Json;

/**
 * Class DomBuilder
 * @package ByTIC\Html\Dom
 */
class DomBuilder
{
    /**
     * Create a html element.
     *
     * @param string $name Element tag name.
     * @param mixed $content Element content.
     * @param array $attribs Element attributes.
     * @param bool $forcePair Force pair it.
     *
     * @return  string Created element string.
     */
    public static function create($name, $content = '', $attribs = [], $forcePair = false)
    {
        $name = trim($name);

        $tag = '<' . $name;

        $attribs = static::buildAttributes($attribs);
        $tag .= $attribs ? ' ' . $attribs : '';

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
     * @param array|DomAttributes $attribs
     *
     * @return  string
     */
    public static function buildAttributes($attribs)
    {
        $attribs = static::prepareAttributes($attribs);

        $string = [];

        foreach ($attribs as $key => $value) {
            $string = array_merge($string, static::renderAttribute($key, $value));
        }
        $string = array_filter($string);

        return implode(' ', $string);
    }

    /**
     * @param $attribute
     * @param $value
     * @return string[]
     */
    protected static function renderAttribute($attribute, $value)
    {
        if ($value === null || $value === false) {
            return [];
        }

        if ($value === true) {
            return [$attribute];
        }

        if (is_array($value)) {
            return static::renderAttributeArray($attribute, $value);
        }

        return [$attribute . '=' . static::quote($value)];
    }

    /**
     * @param $attribute
     * @param $value
     * @return string[]
     */
    protected static function renderAttributeArray($attribute, $value): array
    {
        return [" $attribute='" . Json::htmlEncode($value) . "'"];
    }

    /**
     * @param $attributes
     * @return array|mixed
     */
    protected static function prepareAttributes($attributes)
    {
        $attributes = is_object($attributes) ? $attributes->toArray() : $attributes;
        return static::orderAttributes($attributes);
    }

    /**
     * @param $attributes
     * @return array|mixed
     */
    protected static function orderAttributes($attributes)
    {
        // Sorting by attribute name provides predictable output for testing.
        ksort($attributes);
        return $attributes;
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
        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        return '"' . $value . '"';
    }
}
