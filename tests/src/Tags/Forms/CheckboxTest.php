<?php

namespace ByTIC\Html\Tests\Tags\Forms;

use ByTIC\Html\Tags\Forms\Checkbox;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class InputTest
 * @package ByTIC\Html\Tests\Tags
 */
class CheckboxTest extends AbstractTest
{
    /**
     * @param $name
     * @param $value
     * @param $options
     * @param $html
     * @dataProvider data_hidden
     */
    public function test_create($name, $value, $options, $html)
    {
        $hidden = Checkbox::create($name, $value, $options);
        self::assertInstanceOf(Checkbox::class, $hidden);
        self::assertEquals($html, (string)$hidden);
    }

    public function data_hidden(): array
    {
        return [
            [
                'test',
                'value',
                [],
                '<input type="checkbox" name="test" value="value" />'
            ],
            [
                'test',
                'value',
                ['checked' => true],
                '<input type="checkbox" name="test" value="value" checked="checked" />'
            ],
            [
                'test',
                'value',
                ['checked' => false],
                '<input type="checkbox" name="test" value="value" />'
            ],
            [
                'types[]',
                'one',
                ['class' => 'form-check-input', 'id' => 'activity-type'],
                '<input type="checkbox" id="activity-type" class="form-check-input" name="types[]" value="one" />'
            ]
        ];
    }
}