<?php

namespace ByTIC\Html\Tags\Forms;

use ByTIC\Html\Html\HtmlElement;
use ByTIC\Html\Tags\AbstractTag;
use Traversable;

/**
 * Class Input
 * @package ByTIC\Html\Tags\Forms
 */
class Select extends AbstractTag
{
    /**
     * Property selected.
     *
     * @var  mixed
     */
    protected $selected = null;

    /**
     * Element content.
     *
     * @var  Option[]
     */
    protected $content;

    /**
     * Property multiple.
     *
     * @var  bool
     */
    protected $multiple;

    /**
     * Constructor
     *
     * @param string $name
     * @param mixed|null $options
     * @param array $attribs
     * @param mixed $selected
     * @param bool $multiple
     */
    public function __construct($name, $options = [], $attribs = [], $selected = null, $multiple = false)
    {
        $attribs['name'] = $name;
        parent::__construct('select', (array)$options, $attribs);

        $this->setSelected($selected);
        $this->setMultiple($multiple);
    }

    /**
     * @param $name
     * @param array $options
     * @param array $attribs
     * @param null $selected
     * @param false $multiple
     * @return Select
     */
    public static function create($name, $options = [], $attribs = [], $selected = null, $multiple = false): Select
    {
        return new static($name, $options, $attribs, $selected, $multiple);
    }

    public function addOptionsFromTraversable(Traversable $collection, $text = null, $value = null)
    {
        foreach ($collection as $item) {
            $this->option(
                data_get($item, $text),
                data_get($item, $value)
            );
        }
    }

    /**
     * addOption
     *
     * @param Option $option
     * @param string $group
     *
     * @return  self
     */
    public function addOption(Option $option, $group = null)
    {
        if ($group) {
            $content = $this->content[$group];

            $content[] = $option;

            $this->content[$group] = $content;
        } else {
            $this->content[] = $option;
        }

        return $this;
    }

    /**
     * option
     *
     * @param string $text
     * @param string $value
     * @param array $attribs
     * @param string $group
     *
     * @return  self
     */
    public function option($text = null, $value = null, $attribs = [], $group = null)
    {
        return $this->addOption(new Option($text, $value, $attribs), $group);
    }

    /**
     * toString
     *
     * @param bool $forcePair
     *
     * @return  string
     */
    public function toString($forcePair = false)
    {
        $tmpContent = clone $this->getContent();
        $tmpName = $this->getAttribute('name');

        $this->prepareOptions();

        if ($this->multiple) {
            $this->setAttribute('name', $this->getAttribute('name') . '[]');
        }

        $html = parent::toString($forcePair);

        $this->setAttribute('name', $tmpName);
        $this->setContent($tmpContent);

        return $html;
    }

    /**
     * prepareOptions
     *
     * @return  void
     */
    protected function prepareOptions()
    {
        foreach ($this->content as $name => $option) {
            // Array means it is a group
            if (is_array($option)) {
                foreach ($option as &$opt) {
                    if ($this->checkSelected($opt->getValue())) {
                        $opt['selected'] = 'selected';
                    }
                }

                $this->content[$name] = new HtmlElement('optgroup', $option, ['label' => $name]);
            } elseif ($this->checkSelected($option->getValue())) {
                $option['selected'] = 'selected';
            }
        }
    }

    /**
     * checkSelected
     *
     * @param mixed $value
     *
     * @return  bool
     */
    protected function checkSelected($value)
    {
        $selected = $this->getSelected();
        if ($selected === false) {
            return false;
        }
        $value = (string)$value;

        if ($this->multiple) {
            return in_array($value, (array) $selected);
        } else {
            return $value == (string) $selected;
        }
    }

    /**
     * Method to get property Selected
     *
     * @return  mixed
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Method to set property selected
     *
     * @param mixed $selected
     *
     * @return  static  Return self to support chaining.
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Method to get property Multiple
     *
     * @return  bool
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Method to set property multiple
     *
     * @param bool $multiple
     *
     * @return  static  Return self to support chaining.
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        $this->setAttribute('multiple', (bool)$multiple);

        return $this;
    }
}
