<?php

declare(strict_types=1);

namespace ByTIC\Html\Tests\Html;

use ByTIC\Html\Html\HtmlElement;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class HtmlElementTest
 * @package ByTIC\Html\Tests\Html
 */
class HtmlElementTest extends AbstractTest
{
    public function test_addClass()
    {
        $element = new HtmlElement('img', null, []);
        self::assertEquals('<img />', (string) $element);

        $element->addClass('test');
        self::assertEquals('<img class="test" />', (string) $element);

        $element->addClass('test');
        $element->addClass('test2');
        self::assertEquals('<img class="test test2" />', (string) $element);
    }
}
