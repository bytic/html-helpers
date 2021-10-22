<?php

namespace ByTIC\Html\Tests\Tags;

use ByTIC\Html\Tags\Anchor;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class AnchorTest
 * @package ByTIC\Html\Tests\Tags
 */
class AnchorTest extends AbstractTest
{
    /**
     * @param $name
     * @param $value
     * @param $options
     * @param $html
     * @dataProvider data_hidden
     */
    public function test_url($url, $label, $options, $html)
    {
        $anchor = Anchor::url($url, $label, $options);
        self::assertInstanceOf(Anchor::class, $anchor);
        self::assertEquals($html, (string)$anchor);
    }

    public function data_hidden(): array
    {
        return [
            ['http://google.com', 'value', [], '<a href="http://google.com">value</a>'],
            ['javascript:', 'value', [], '<a href="javascript:">value</a>'],
            ['javascript:', 'value', ['target' => '_blank'], '<a href="javascript:" target="_blank">value</a>'],
        ];
    }

    public function test_target()
    {
        $anchor = Anchor::url('#', 'test')->targetBank();
        self::assertEquals(
            '<a href="#" target="_blank">test</a>',
            (string)$anchor
        );
    }
}
