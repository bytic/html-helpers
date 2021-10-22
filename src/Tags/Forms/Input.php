<?php

namespace ByTIC\Html\Tags\Forms;

use ByTIC\Html\Tags\AbstractTag;

/**
 * Class Input
 * @package ByTIC\Html\Tags\Forms
 */
class Input extends AbstractTag
{
    /**
     * @param $name
     * @param null $value
     * @param array $options
     * @return mixed
     */
    public static function hidden($name, $value = null, $options = [])
    {
        return static::input('hidden', $name, $value, $options);
    }

    /**
     * @param $name
     * @param null $value
     * @param array $options
     * @return mixed
     */
    public static function text($name, $value = null, $options = [])
    {
        return static::input('text', $name, $value, $options);
    }

    /**
     * @param $name
     * @param null $value
     * @param array $options
     * @return mixed
     */
    public static function date($name, $value = null, $options = [])
    {
        return static::input('date', $name, $value, $options);
    }

    /**
     * @param $type
     * @param null $name
     * @param null $value
     * @param array $options
     * @return mixed
     */
    protected static function input($type, $name = null, $value = null, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        $options['name'] = $name;
        $options['value'] = $value === null ? null : (string) $value;
        return static::tag('input', null, $options);
    }
}
