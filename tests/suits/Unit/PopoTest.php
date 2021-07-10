<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Popo\Example\Foo;
use PHPUnit\Framework\TestCase;
use PopoTestsSuites\Functional\PopoFacadeTest;

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
                'value' => PopoFacadeTest::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz'
                    ]
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
            'value' => PopoFacadeTest::TEST_BUZZ,
            'bar' => [
                'title' => 'Bar Bar',
                'buzz' => [
                    'value' => 'Foo Bar Buzz'
                ]
            ],
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }
}
