<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Config;
use Popo\Schema\Property;
use Popo\Schema\Schema;

/**
 * @group unit
 */
class SchemaTest extends TestCase
{
    public function test_array(): void
    {
        $property = (new Property)
            ->fromArray(
                [
                    'name' => 'fooId',
                    'type' => 'int',
                ]
            );

        $schema = (new Schema)
            ->setNamespace('App\\Popo')
            ->setSchemaName('Example')
            ->setName('Foo')
            ->setPropertyCollection(['fooId' => $property]);
        $schema
            ->getConfig()->setExtend('fooBar');

        $this->assertEquals(
            [
                'namespace' => 'App\\Popo',
                'schemaName' => 'Example',
                'name' => 'Foo',
                'propertyCollection' => ['fooId' => $property],
                'config' => $schema->getConfig(),
            ],
            $schema->toArray()
        );
    }
}
