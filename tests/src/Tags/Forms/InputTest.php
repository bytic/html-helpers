<?php

namespace ByTIC\Html\Tests\Tags\Forms;

use ByTIC\Html\Tags\Forms\Input;
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
        $hidden = Input::hidden($name, $value, $options);
        self::assertInstanceOf(Input::class, $hidden);
        self::assertEquals($html, (string) $hidden);
    }

    public function data_hidden(): array
    {
        return [
            ['test', 'value', [], '<input type="hidden" name="test" value="value" />']
        ];
    }

    /**
     * @param $name
     * @param $value
     * @param $options
     * @param $html
     * @dataProvider data_date
     */
    public function test_date($name, $value, $options, $html)
    {
        $hidden = Input::date($name, $value, $options);
        self::assertInstanceOf(Input::class, $hidden);
        self::assertEquals($html, (string) $hidden);
    }

    public function data_date(): array
    {
        return [
            ['test', 'value', [], '<input type="date" name="test" value="value" />']
        ];
    }
}