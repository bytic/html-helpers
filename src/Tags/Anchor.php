<?php

namespace ByTIC\Html\Tags;

/**
 * Class Anchor
 * @package ByTIC\Html\Tags
 */
class Anchor extends AbstractTag
{
    public const TARGET_SELF = "_self";
    public const TARGET_BLANK = "_blank";
    public const TARGET_PARENT = "_parent";
    public const TARGET_TOP = "_top";

    /**
     * @param $url
     * @param null $label
     * @param array $options
     * @return AbstractTag|Anchor
     */
    public static function url($url, $label = null, $options = []): AbstractTag
    {
        $options['href'] = $url;
        return static::tag('a', $label, $options);
    }

    /**
     * @return $this|Anchor
     */
    public function targetBank()
    {
        return $this->target(self::TARGET_BLANK);
    }

    /**
     * @param string $target
     * @return $this
     */
    public function target(string $target): Anchor
    {
        $this->setAttribute('target', $target);
        return $this;
    }
}