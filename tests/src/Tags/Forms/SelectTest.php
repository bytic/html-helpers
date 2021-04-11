<?php

namespace ByTIC\Html\Tests\Tags\Forms;

use ByTIC\Html\Tags\Forms\Select;
use ByTIC\Html\Tests\AbstractTest;

/**
 * Class SelectTest
 * @package ByTIC\Html\Tests\Tags\Forms
 */
class SelectTest extends AbstractTest
{
    public function test_create_basic()
    {
        $element = Select::create('test');
        self::assertInstanceOf(Select::class, $element);
        self::assertEquals('<select name="test"></select>', (string)$element);

        $element->option('Option1', 'val1');
        self::assertEquals('<select name="test"><option value="val1">Option1</option></select>', (string)$element);
    }
}