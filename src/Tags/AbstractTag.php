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
     */
    public static function tag($name, $content = null, $options = []): AbstractTag
    {
        return (new static($name, $content, $options));
    }
}