<?php

namespace ByTIC\Html\Html;

use ByTIC\Html\Dom\DomBuilder;

/**
 * Class HtmlBuilder
 * @package ByTIC\Html
 */
class HtmlBuilder extends DomBuilder
{
    /**
     * @var array list of void elements (element name => 1)
     * @see http://www.w3.org/TR/html-markup/syntax.html#void-element
     */
    public static $voidElements = [
        'area' => 1,
        'base' => 1,
        'br' => 1,
        'col' => 1,
        'command' => 1,
        'embed' => 1,
        'hr' => 1,
        'img' => 1,
        'input' => 1,
        'keygen' => 1,
        'link' => 1,
        'meta' => 1,
        'param' => 1,
        'source' => 1,
        'track' => 1,
        'wbr' => 1,
    ];

    /**
     * @var array the preferred order of attributes in a tag. This mainly affects the order of the attributes
     * that are rendered by [[renderTagAttributes()]].
     */
    public static $attributeOrder = [
        'type',
        'id',
        'class',
        'name',
        'value',

        'href',
        'src',
        'srcset',
        'form',
        'action',
        'method',

        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',

        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',

        'alt',
        'title',
        'rel',
        'media',
    ];

    /**
     * @var array list of tag attributes that should be specially handled when their values are of array type.
     * In particular, if the value of the `data` attribute is `['name' => 'xyz', 'age' => 13]`, two attributes
     * will be generated instead of one: `data-name="xyz" data-age="13"`.
     * @since 2.0.3
     */
    public static $dataAttributes = ['aria', 'data', 'data-ng', 'ng'];

    /**
     * Property trueValueMapping.
     *
     * @var  array
     */
    protected static $trueValueMapping = [
        'readonly' => 'readonly',
        'disabled' => 'disabled',
        'multiple' => true,
        'checked' => 'checked',
        'selected' => 'selected',
    ];

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
        $paired = $forcePair ?: !isset(static::$voidElements[strtolower($name)]);

        return parent::create($name, $content, $attribs, $paired);
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
        $attribs = static::orderAttributes($attribs);
        $attribs = static::mapAttrValues($attribs);

        $html = '';
        foreach ($attribs as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } elseif (is_array($value)) {
                if (in_array($name, static::$dataAttributes)) {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $html .= " $name-$n='" . Json::htmlEncode($v) . "'";
                        } elseif (is_bool($v)) {
                            if ($v) {
                                $html .= " $name-$n";
                            }
                        } elseif ($v !== null) {
                            $html .= " $name-$n=\"" . static::encode($v) . '"';
                        }
                    }
                } elseif ($name === 'class') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(implode(' ', $value)) . '"';
                } elseif ($name === 'style') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(static::cssStyleFromArray($value)) . '"';
                } else {
                    $html .= " $name='" . Json::htmlEncode($value) . "'";
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . '"';
            }
        }

        return $html;
    }

    /**
     * @param $attributes
     * @return array|mixed
     */
    protected static function orderAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $sorted = [];
            foreach (static::$attributeOrder as $name) {
                if (isset($attributes[$name])) {
                    $sorted[$name] = $attributes[$name];
                }
            }
            $attributes = array_merge($sorted, $attributes);
        }
        return $attributes;
    }

    /**
     * mapAttrValues
     *
     * @param array $attribs
     *
     * @return  mixed
     */
    protected static function mapAttrValues($attribs)
    {
        foreach (static::$trueValueMapping as $key => $value) {
            $attribs[$key] = !empty($attribs[$key]) ? $value : null;
        }

        return $attribs;
    }

    /**
     * Encodes special characters into HTML entities.
     *
     * @param string $content the content to be encoded
     * @param bool $doubleEncode whether to encode HTML entities in `$content`. If false,
     * HTML entities in `$content` will not be further encoded.
     * @return string the encoded content
     * @see decode()
     * @see https://secure.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function encode(string $content, $doubleEncode = true): string
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * Decodes special HTML entities back to the corresponding characters.
     * This is the opposite of [[encode()]].
     * @param string $content the content to be decoded
     * @return string the decoded content
     * @see encode()
     * @see https://secure.php.net/manual/en/function.htmlspecialchars-decode.php
     */
    public static function decode(string $content): string
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }
}
