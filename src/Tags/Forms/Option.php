<?php

namespace ByTIC\Html\Tags\Forms;

use ByTIC\Html\Html\HtmlElement;

/**
 * Class Option
 * @package ByTIC\Html\Tags\Forms
 */
class Option extends HtmlElement
{
    /**
     * Property value.
     *
     * @var  string
     */
    protected $value = '';

    /**
     * Property attributes.
     *
     * @var  string
     */
    protected $attributes = [];

    /**
     * @param string $text
     * @param string $value
     * @param array  $attribs
     */
    public function __construct($text = null, $value = null, $attribs = [])
    {
        $this->value = $value;

        $attribs['value'] = $value;

        parent::__construct('option', $text, $attribs);
    }

    /**
     * Method to get property Value
     *
     * @return  string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Method to set property value
     *
     * @param   string $value
     *
     * @return  static  Return self to support chaining.
     */
    public function setValue($value)
    {
        $this->value = $value;

        $this['value'] = $value;

        return $this;
    }
}
