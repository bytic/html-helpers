<?php

namespace ByTIC\Html\Tags;

/**
 * Class Iframe
 * @package ByTIC\Html\Tags
 */
class Iframe extends AbstractTag
{

    /**
     * @param $url
     * @param null $label
     * @param array $options
     * @return AbstractTag|Anchor
     */
    public static function src($url, $label = null, $options = []): AbstractTag
    {
        $options['src'] = $url;
        return static::tag('iframe', $label, $options);
    }

}