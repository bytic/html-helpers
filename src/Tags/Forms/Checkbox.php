<?php

namespace ByTIC\Html\Tags\Forms;

/**
 * Class Checkbox
 * @package ByTIC\Html\Tags\Forms
 */
class Checkbox extends Input
{
    /**
     * @param $name
     * @param null $value
     * @param array $options
     * @return mixed
     */
    public static function create($name, $value = null, $options = [])
    {
        return static::input('checkbox', $name, $value, $options);
    }
}
