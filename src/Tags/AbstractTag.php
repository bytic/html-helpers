<?php

namespace ByTIC\Html\Tags;

use ByTIC\Html\Html\HtmlElement;

/**
 * Class AbstractTag
 * @package ByTIC\Html\Tags
 */
abstract class AbstractTag extends HtmlElement
{
    /**
     * @param $name
     * @param string $content
     * @param array $options
     * @return string
     */
    public static function tag($name, $content = null, $options = [])
    {
        return (new static($name, $content, $options));
    }
}