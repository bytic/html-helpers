<?php

declare(strict_types=1);

namespace ByTIC\Html\Html;

use ByTIC\Html\Dom\DomAttributes;
use ByTIC\Html\Dom\DomBuilder;
use Nip\Utility\Json;

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
     * @param string $name Element tag name.
     * @param mixed $content Element content.
     * @param array $attribs Element attributes.
     * @param bool $forcePair Force pair it.
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
     * @param $attribute
     * @param $value
     * @return  string[]
     */
    public static function renderAttributeArray($attribute, $value): array
    {
        if (in_array($attribute, static::$dataAttributes)) {
            $return = [];
            foreach ($value as $dataKey => $dataValue) {
                if (is_array($dataValue)) {
                    $return[] = "$attribute-$dataKey='" . Json::htmlEncode($dataValue) . "'";
                } elseif (is_bool($dataValue)) {
                    if ($dataValue) {
                        $return[] = " $attribute-$dataKey";
                    }
                } elseif ($dataValue !== null) {
                    $return[] = "$attribute-$dataKey=\"" . static::encode($dataValue) . '"';
                }
            }
            return $return;
        }

        if ($attribute === 'class') {
            if (empty($value)) {
                return [];
            }
            return ["$attribute=\"" . static::encode(implode(' ', $value)) . '"'];
        }
        if ($attribute === 'style') {
            if (empty($value)) {
                return [];
            }
            return ["$attribute=\"" . static::encode(static::cssStyleFromArray($value)) . '"'];
        }

        return parent::renderAttributeArray($attribute, $value);
    }

    /**
     * @param $attributes
     * @return array|mixed
     */
    protected static function prepareAttributes($attributes)
    {
        $attributes = parent::prepareAttributes($attributes);
        return static::mapAttrValues($attributes);
    }

    /**
     * @param $attributes
     * @return array|mixed
     */
    protected static function orderAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $attributes = parent::orderAttributes($attributes);

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
     * Converts a CSS style array into a string representation.
     *
     * For example,
     *
     * ```php
     * // width: 100px; height: 200px;
     * Html::cssStyleFromArray(['width' => '100px', 'height' => '200px']);
     * ```
     *
     * @param array<string, string> $style The CSS style array. The array keys are the CSS property names,
     * and the array values are the corresponding CSS property values.
     *
     * @return string|null The CSS style string. If the CSS style is empty, a null will be returned.
     * @see cssStyleToArray()
     *
     */
    public static function cssStyleFromArray(array $style): ?string
    {
        $result = '';
        foreach ($style as $name => $value) {
            $result .= "$name: $value; ";
        }

        // Return null if empty to avoid rendering the "style" attribute.
        return $result === '' ? null : rtrim($result);
    }

    /**
     * Converts a CSS style string into an array representation.
     *
     * The array keys are the CSS property names, and the array values are the corresponding CSS property values.
     *
     * For example,
     *
     * ```php
     * // ['width' => '100px', 'height' => '200px']
     * HtmlBuilder::cssStyleToArray('width: 100px; height: 200px;');
     * ```
     *
     * @param string|\Stringable $style The CSS style string.
     *
     * @return array The array representation of the CSS style.
     * @psalm-return array<string, string>
     * @see cssStyleFromArray()
     *
     */
    public static function cssStyleToArray($style): array
    {
        $result = [];
        foreach (explode(';', (string)$style) as $property) {
            $property = explode(':', $property);
            if (count($property) > 1) {
                $result[trim($property[0])] = trim($property[1]);
            }
        }

        return $result;
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
