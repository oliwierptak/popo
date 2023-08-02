<?php declare(strict_types = 1);

namespace PopoTestSuite\Schema\Generator;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Generator\SchemaGenerator;
use Popo\Schema\Inspector\SchemaInspector;

class SchemaGeneratorTest extends TestCase
{
    public function test_parse_attribute(): void
    {
        $schemaGenerator = new SchemaGenerator(
            new SchemaInspector()
        );

        $result = $schemaGenerator->parseAttributes("#[ORM\Table(name: 'country')]", []);

        $this->assertEquals('ORM\Table', $result[0]->getName());
        $this->assertEquals("name: 'country'", $result[0]->getArguments()[0]);
    }

    public function test_parse_attributes(): void
    {
        $schemaGenerator = new SchemaGenerator(
            new SchemaInspector()
        );

        $result = $schemaGenerator->parseAttributes(null, [[
            'name' => 'Doctrine\ORM\Mapping\Column',
            'value' => [ 'length' => 255 ]
        ]]);

        $this->assertEquals('Doctrine\ORM\Mapping\Column', $result[0]->getName());
        $this->assertEquals("length: 255", $result[0]->getArguments()[0]->__toString());
    }
}
