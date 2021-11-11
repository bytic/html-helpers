<?php

namespace ByTIC\Html\Tests\Tags;

use ByTIC\Html\Tags\Iframe;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class IframeTest
 * @package ByTIC\Html\Tests\Tags
 */
class IframeTest extends AbstractTest
{
    /**
     */
    public function test_src()
    {
        $tag = Iframe::src('#', 'test');
        self::assertEquals(
            '<iframe src="#">test</iframe>',
            (string)$tag
        );

        $tag = Iframe::src('#');
        self::assertEquals(
            '<iframe src="#"></iframe>',
            (string)$tag
        );
    }

}
