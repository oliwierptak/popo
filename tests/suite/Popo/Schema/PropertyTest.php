<?php

declare(strict_types = 1);

namespace PopoTestSuite\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Property;

/**
 * @group unit
 */
class PropertyTest extends TestCase
{
    public function test_setters_getters(): void
    {
        $property = (new Property)
            ->setName('FooBar')
            ->setType('bool')
            ->setItemType('Buzz::class')
            ->setItemName('BuzzItem')
            ->setComment('Lorem ipsum');

        $this->assertEquals('FooBar', $property->getName());
        $this->assertEquals('bool', $property->getType());
        $this->assertEquals('Lorem ipsum', $property->getComment());
        $this->assertEquals('Buzz::class', $property->getItemType());
        $this->assertEquals('BuzzItem', $property->getItemName());
    }

    public function test_array_partial(): void
    {
        $property = new Property();

        $property->fromArray(
            [
                'name' => 'foo',
            ]
        );

        $this->assertEquals(
            [
                'name' => 'foo',
                'type' => 'string',
                'comment' => null,
                'itemType' => null,
                'itemName' => null,
                'default' => null,
                'extra' => null,

            ],
            $property->toArray()
        );
    }

    public function test_array_full(): void
    {
        $property = new Property();

        $property->fromArray(
            [
                'name' => 'records',
                'type' => 'array',
                'comment' => 'Lorem ipsum',
                'itemType' => 'string',
                'itemName' => 'record',
                'default' => [],
                'extra' => null,
            ]
        );

        $this->assertEquals(
            [
                'name' => 'records',
                'type' => 'array',
                'comment' => 'Lorem ipsum',
                'itemType' => 'string',
                'itemName' => 'record',
                'default' => [],
                'extra' => null,

            ],
            $property->toArray()
        );
    }
}
