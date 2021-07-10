<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Property;
use Popo\Schema\PropertySchema;
use Popo\Schema\Schema;

/**
 * @group unit
 */
class SchemaTest extends TestCase
{
    public function test_array(): void
    {
        $propertySchema = (new PropertySchema)
            ->fromArray(
                [
                    'name' => 'fooId',
                    'type' => 'int',
                    'docblock' => 'Lorem ipsum',
                ]
            );
        $property = (new Property)
            ->setSchema($propertySchema)
            ->setValue(123);

        $schema = (new Schema)
            ->setNamespace('App\\Popo')
            ->setSchemaName('Example')
            ->setName('Foo')
            ->setPropertyCollection(['fooId' => $property]);

        $this->assertEquals(
            [
                'schemaName' => 'Example',
                'name' => 'Foo',
                'propertyCollection' => ['fooId' => $property],
                'namespace' => 'App\\Popo',
                'configurator' => null,
            ],
            $schema->toArray()
        );
    }
}
