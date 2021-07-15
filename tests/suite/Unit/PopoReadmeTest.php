<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Example\Readme\Foo;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class PopoReadmeTest extends TestCase
{
    public function test_example_from_array(): void
    {
        $data = [
            'title' => 'A title',
            'bar' => [
                'title' => 'Bar lorem ipsum',
            ],
        ];

        $foo = (new Foo)->fromArray($data);

        $this->assertEquals('A title', $foo->getTitle());
        $this->assertEquals('Bar lorem ipsum', $foo->requireBar()->getTitle());
    }

    public function test_example_to_array(): void
    {
        $foo = (new Foo);
        $foo->requireBar()->setTitle('new value');
        $data = $foo->toArray();

        $expectedData = [
            'title' => null,
            'bar' => [
                'title' => 'new value',
            ],
        ];

        $this->assertEquals($expectedData, $data);
    }
}
