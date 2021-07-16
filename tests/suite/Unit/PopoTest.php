<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Example\Popo\Bar;
use App\Example\Popo\Foo;
use App\Example\Popo\Fizz\Buzz;
use App\ExampleInterface;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @group unit
 */
class PopoTest extends TestCase
{
    public function test_toArray(): void
    {
        $foo = (new Foo());

        $this->assertEquals(
            [
                'fooId' => null,
                'title' => 'Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                    ],
                    'buzzCollection' => [],
                ],
                'test' => null
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $foo = (new Foo);

        $expected = [
            'fooId' => null,
            'title' => 'Lorem Ipsum',
            'value' => ExampleInterface::TEST_BUZZ,
            'bar' => [
                'title' => 'Bar Bar',
                'buzz' => [
                    'value' => 'Foo Bar Buzz',
                ],
                'buzzCollection' => [],
            ],
            'test' => null
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->isNew());

        $foo->setValue(1);
        $this->assertFalse($foo->isNew());
    }

    public function test_require_default(): void
    {
        $foo = (new Foo);

        $this->assertEquals('Hakuna Matata', $foo->getTitle());
        $this->assertEquals('Buzzzzz', $foo->requireBar()->requireBuzz()->getValue());
    }

    public function test_require_fooId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required property "fooId" is not set');

        $foo = (new Foo);

        $foo->requireFooId();
    }

    public function test_require(): void
    {
        $foo = (new Foo);

        $this->assertNull($foo->getBar());
        $this->assertInstanceOf(Bar::class, $foo->requireBar());
        $this->assertEquals('Hakuna Matata', $foo->requireTitle());
        $this->assertEquals(ExampleInterface::TEST_BUZZ, $foo->requireValue());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required property "fooId" is not set');
        $this->expectExceptionMessage('Required property "value" is not set');

        $foo = (new Foo)->setValue(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new Foo)
            ->setFooId(1)
            ->setTest('abc');

        $foo->requireAll();

        $this->assertEquals(1, $foo->getFooId());
    }

    public function test_require_all_collection_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required property "buzzCollection" is not set');

        $bar = (new Bar());

        $collection = $bar->requireBuzzCollection();
    }

    public function test_require_all_collection(): void
    {
        $bar = (new Bar)->setBuzzCollection([
            new Buzz
        ]);

        $this->assertNotEmpty($bar->getBuzzCollection());
    }

    public function test_has(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->hasValue());

        $foo->setValue(null);
        $this->assertFalse($foo->hasValue());
    }

    public function test_has_collection(): void
    {
        $foo = (new Foo);

        $this->assertFalse($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->setBuzzCollection([new Buzz]);
        $this->assertTrue($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->setBuzzCollection([]);
        $this->assertFalse($foo->requireBar()->hasBuzzCollection());
    }

    public function test_add_collection_item(): void
    {
        $foo = (new Foo);

        $this->assertFalse($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->addBuzz(new Buzz());
        $this->assertTrue($foo->requireBar()->hasBuzzCollection());
    }
}
