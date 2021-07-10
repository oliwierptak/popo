<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use PHPUnit\Framework\TestCase;
use Popo\Schema\PropertySchema;

/**
 * @group unit
 */
class PropertySchemaTest extends TestCase
{
    public function test_array_partial(): void
    {
        $propertySchema = new PropertySchema();

        $propertySchema->fromArray(
            [
                'name' => 'foo',
            ]
        );

        $this->assertEquals(
            [
                'name' => 'foo',
                'type' => 'string',
                'docblock' => null,
                'itemType' => null,
                'itemName' => null,
                'default' => null,

            ],
            $propertySchema->toArray()
        );
    }

    public function test_array_full(): void
    {
        $propertySchema = new PropertySchema();

        $propertySchema->fromArray(
            [
                'name' => 'records',
                'type' => 'array',
                'docblock' => 'Lorem ipsum',
                'itemType' => 'string',
                'itemName' => 'record',
                'default' => [],
            ]
        );

        $this->assertEquals(
            [
                'name' => 'records',
                'type' => 'array',
                'docblock' => 'Lorem ipsum',
                'itemType' => 'string',
                'itemName' => 'record',
                'default' => [],

            ],
            $propertySchema->toArray()
        );
    }
}
