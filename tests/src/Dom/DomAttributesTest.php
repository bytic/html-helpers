<?php

declare(strict_types=1);

namespace ByTIC\Html\Tests\Dom;

use ByTIC\Html\Dom\DomAttributes;
use ByTIC\Html\Tests\AbstractTest;

/**
 *
 */
class DomAttributesTest extends AbstractTest
{
    /** @test */
    public function it_starts_empty()
    {
        $attributes = new DomAttributes();

        static::assertTrue($attributes->isEmpty());
        static::assertEmpty($attributes->toArray());
        static::assertEmpty($attributes->toString());
    }

    /** @test */
    public function it_construct_from_object()
    {
        $attributes = new DomAttributes(['class' => ['card'], 'disabled' => 'disabled']);
        $attributes2 = new DomAttributes($attributes);

        static::assertSame($attributes->toString(), $attributes2->toString());
    }

    /** @test */
    public function it_is_initializable()
    {
        $attributes = new DomAttributes(['class' => ['card'], 'disabled' => 'disabled']);

        self::assertInstanceOf(DomAttributes::class, $attributes);

        self::assertSame('card', (string)$attributes->offsetGet('class'));
        self::assertTrue($attributes->hasClass('card'));
        self::assertSame('disabled', $attributes->offsetGet('disabled'));
        self::assertEquals('class="card" disabled="disabled"', (string)$attributes);
    }

    /** @test */
    public function it_can_have_an_attribute_added_to_it()
    {
        $attributes = new DomAttributes();
        $attributes->setAttribute('id', 'card');

        static::assertSame('card', $attributes->offsetGet('id'));
        static::assertEquals('id="card"', (string)$attributes);
    }

    /** @test */
    public function it_accepts_attributes_without_values()
    {
        $attributes = new DomAttributes();

        self::assertSame(
            ['required' => null],
            $attributes->setAttribute('required')->toArray()
        );
    }

    /** @test */
    public function it_can_have_an_attribute_removed_from_it()
    {
        $attributes = new DomAttributes();
        $attributes->setAttribute('id', 'card');
        $attributes->removeAttribute('id');

        static::assertNull($attributes->offsetGet('id'));
        static::assertEquals('', (string)$attributes);
    }

    /** @test */
    public function it_can_have_classes_added_as_strings()
    {
        $attributes = new DomAttributes();

        $attributes->addClass('card');
        $attributes->addClass('card--wide');

        static::assertEquals('card card--wide', (string)$attributes->offsetGet('class'));
        static::assertTrue($attributes->hasClass('card'));
        static::assertEquals('class="card card--wide"', (string)$attributes);
    }


    /** @test */
    public function it_can_have_classes_added_as_an_array()
    {
        $attributes = new DomAttributes();

        static::assertSame(
            ['foo', 'bar'],
            $attributes->addClass(['foo', 'bar'])->toArray()['class']
        );
    }

    /** @test */
    public function it_can_have_a_class_removed_from_it()
    {
        $attributes = new DomAttributes(['class' => ['card', 'card--wide']]);
        static::assertSame('card card--wide', (string)$attributes->offsetGet('class'));

        $attributes->removeClass('card--wide');

        static::assertSame('card', (string)$attributes->offsetGet('class'));
        static::assertEquals('class="card"', (string)$attributes);
    }

    /** @test */
    public function it_can_have_all_classes_removed_from_it()
    {
        $attributes = new DomAttributes(['class' => ['card', 'card--wide']]);

        $attributes->removeClass('card--wide');
        $attributes->removeClass('card');

        static::assertEmpty((string)$attributes->offsetGet('class'));
        static::assertEquals('', (string)$attributes);
    }

    /** @test */
    public function it_can_have_attributes_merged_into_it()
    {
        $attributes = new DomAttributes(['class' => ['card', 'card--wide'], 'id' => 'card-1']);
        $attributes_copy = new DomAttributes(['class' => ['card', 'card--wide'], 'id' => 'card-1']);

        // Test String
        $attributes->mergeAttribute('aria-description', 'A description.');
        $attributes_copy->merge('aria-description', 'A description.');

        static::assertEquals((string)$attributes, (string)$attributes_copy);
        static::assertEquals(
            'aria-description="A description." class="card card--wide" id="card-1"',
            (string)$attributes
        );

        // Test String Classes with Spaces
        $attributes->mergeAttribute('class', 'one two three');
        $attributes_copy->merge('class', 'one two three');

        static::assertEquals((string)$attributes, (string)$attributes_copy);
        static::assertEquals(
            'aria-description="A description." class="card card--wide one two three" id="card-1"',
            (string)$attributes
        );

        // Test Array
        $attributes->mergeAttribute('class', ['red', 'blue', 'green']);
        $attributes_copy->merge('class', ['red', 'blue', 'green']);
        static::assertEquals((string)$attributes, (string)$attributes_copy);
        static::assertEquals(
            'aria-description="A description." class="card card--wide one two three red blue green" id="card-1"',
            (string)$attributes
        );
    }

    /** @test */
    public function it_can_return_a_subset_of_keys_from_a_string()
    {
        $attributes = new DomAttributes(['class' => ['one', 'two', 'three'], 'id' => 'card']);

        $only_classes = $attributes->only('class');

        static::assertEquals('class="one two three"', (string)$only_classes);
    }

    /** @test */
    public function it_can_return_a_subset_of_keys_from_an_array()
    {
        $attributes = new DomAttributes(['class' => ['one', 'two', 'three'], 'id' => 'card', 'for' => 'carditem']);

        $only = $attributes->only(['id', 'class']);

        static::assertEquals('class="one two three" id="card"', (string)$only);
    }

    /** @test */
    public function it_can_exclude_a_key_from_a_string()
    {
        $attributes = new DomAttributes(['class' => ['one', 'two', 'three'], 'id' => 'card']);

        $only_id = $attributes->without('id');

        static::assertEquals('class="one two three"', (string)$only_id);
    }

    /** @test */
    public function it_can_exclude_a_subset_of_keys_from_an_array()
    {
        $attributes = new DomAttributes(['class' => ['one', 'two', 'three'], 'id' => 'card', 'for' => 'carditem']);

        $only_for = $attributes->without(['id', 'class']);

        static::assertEquals('for="carditem"', (string)$only_for);
    }

    /** @test */
    public function it_can_convet_to_array()
    {
        $array = ['class' => ['test', 'foe'], 'id' => 'card', 'for' => 'carditem'];
        $attributes = new DomAttributes($array);
        self::assertSame(
            ['class' => ['test', 'foe'], 'id' => 'card', 'for' => 'carditem'],
            $attributes->toArray()
        );
    }

    /** @test */
    public function it_can_be_traversed()
    {
        $array = ['id' => 'card', 'for' => 'carditem'];
        $attributes = new DomAttributes($array);
        foreach ($attributes as $attribute => $value) {
            static::assertEquals($value, $array[$attribute]);
        }
    }
}