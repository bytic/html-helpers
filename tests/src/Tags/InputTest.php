<?php

namespace ByTIC\Html\Tests\Tags;

use ByTIC\Html\Tags\Input;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class InputTest
 * @package ByTIC\Html\Tests\Tags
 */
class InputTest extends AbstractTest
{
    /**
     * @param $name
     * @param $value
     * @param $options
     * @param $html
     * @dataProvider data_hidden
     */
    public function test_hidden($name, $value, $options, $html)
    {
        self::assertSame($html, Input::hidden($name, $value, $options));
    }

    public function data_hidden(): array
    {
        return [
            ['test', 'value', [], '<input type="hidden" name="test" value="value">']
        ];
    }
}