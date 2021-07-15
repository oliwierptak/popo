<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use PHPUnit\Framework\TestCase;
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
                    'name' => 'fooId'
                ]
            );

        $schema = (new Schema)
            ->setSchemaName('Example')
            ->setName('Foo')
            ->setPropertyCollection(['fooId' => $property]);
        $schema
            ->getConfig()->setExtend('fooBar');

        $this->assertEquals('Example', $schema->getSchemaName());
        $this->assertEquals('Foo', $schema->getName());
    }
}
