<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use ExampleBundle\AppWithNamespaceRoot\Example\Bar;
use ExampleBundle\AppWithNamespaceRoot\Example\Foo;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @group unit
 */
class AppWithNamespaceRootTest extends TestCase
{
    public function test_toArray(): void
    {
        $foo = (new Foo());

        $this->assertEquals(
            [
                'title' => null,
                'shouldExecute' => false,
                'bar' => [
                    'title' => null,
                ],
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $foo = (new Foo)->setTitle('Lorem Ipsum');

        $expected = [
            'title' => 'Lorem Ipsum',
            'shouldExecute' => false,
            'bar' => [
                'title' => null,
            ],
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->isNew());

        $foo->setTitle('abc');
        $this->assertFalse($foo->isNew());
    }

    public function test_require_value(): void
    {
        $foo = (new Foo)->setTitle('Hakuna Matata');

        $this->assertEquals('Hakuna Matata', $foo->getTitle());
        $this->assertNull($foo->requireBar()->getTitle());
    }

    public function test_require_fooId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "title" has not been set');

        $foo = (new Foo);

        $foo->requireTitle();
    }

    public function test_require(): void
    {
        $foo = (new Foo);

        $this->assertNull($foo->getBar());
        $this->assertInstanceOf(Bar::class, $foo->requireBar());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "title" has not been set');

        $foo = (new Foo)->setTitle(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new Foo)
            ->setTitle('abc');

        $foo->requireAll();

        $this->assertEquals('abc', $foo->getTitle());
    }

    public function test_boolean_gegter(): void
    {
        $foo = (new Foo)->setShouldExecute(true);

        $this->assertTrue($foo->shouldExecute());
    }
}
