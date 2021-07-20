<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Example\Shared\AnotherFoo;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @group unit
 */
class AnotherPopoTest extends TestCase
{
    public function test_toArray(): void
    {
        $foo = (new AnotherFoo());

        $this->assertEquals(
            [
                'idForAll' => 0,
                'idForAnotherExample' => 100,
                'title' => 'Hakuna Matata',
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $foo = (new AnotherFoo);

        $expected = [
            'idForAll' => 0,
            'idForAnotherExample' => 100,
            'title' => 'Hakuna Matata',
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new AnotherFoo);

        $this->assertTrue($foo->isNew());

        $foo->setTitle('abc');
        $this->assertFalse($foo->isNew());
    }

    public function test_require_default(): void
    {
        $foo = (new AnotherFoo);

        $this->assertEquals('Hakuna Matata', $foo->getTitle());
        $this->assertEquals(100, $foo->getIdForAnotherExample());
    }

    public function test_require_fooId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "idForAnotherExample" has not been set');

        $foo = (new AnotherFoo)->setIdForAnotherExample(null);

        $foo->requireIdForAnotherExample();
    }

    public function test_require(): void
    {
        $foo = (new AnotherFoo);

        $this->assertEquals('Hakuna Matata', $foo->requireTitle());
        $this->assertEquals(100, $foo->requireIdForAnotherExample());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "idForAnotherExample" has not been set');

        $foo = (new AnotherFoo)->setIdForAnotherExample(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new AnotherFoo)
            ->setIdForAnotherExample(1);

        $foo->requireAll();

        $this->assertEquals(1, $foo->getIdForAnotherExample());
    }

    public function test_has(): void
    {
        $foo = (new AnotherFoo);

        $this->assertTrue($foo->hasTitle());

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());
    }
}
