<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Popo\Example\Boo\Foo;
use PHPUnit\Framework\TestCase;
use PopoTestsSuites\Functional\GenerateTest;
use UnexpectedValueException;

/**
 * @group unit
 */
class PopoTest extends TestCase
{
    public function test_toArray(): void
    {
        $foo = (new Foo);

        $this->assertEquals(
            [
                'fooId' => null,
                'title' => 'Hakuna Matata',
                'value' => GenerateTest::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz'
                    ],
                    'buzzCollection' => []
                ],
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
            'value' => GenerateTest::TEST_BUZZ,
            'bar' => [
                'title' => 'Bar Bar',
                'buzz' => [
                    'value' => 'Foo Bar Buzz'
                ],
                'buzzCollection' => []
            ],
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
        $this->assertEquals('Buzzzzz', $foo->getBar()->getBuzz()->getValue());
    }

    public function test_require_all(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required property "fooId" is not set');
        $this->expectExceptionMessage('Required property "value" is not set');
        $this->expectExceptionMessage('Required property "bar" is not set');

        $foo = (new Foo)->setValue(null);

        $foo->requireAll();
    }
}
