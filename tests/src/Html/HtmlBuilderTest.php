<?php

declare(strict_types=1);

namespace ByTIC\Html\Tests\Html;

use ByTIC\Html\Dom\DomAttributes;
use ByTIC\Html\Html\HtmlBuilder;
use ByTIC\Html\Tests\AbstractTest;

/**
 *
 */
class HtmlBuilderTest extends AbstractTest
{
    public function test_buildAttributes_from_object()
    {
        $attributes = new DomAttributes(['class' => 'test foe', 'id' => 'test']);
        self::assertSame('id="test" class="test foe"', HtmlBuilder::buildAttributes($attributes));
    }
}