<?php

declare(strict_types = 1);

namespace PopoTestSuite\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\LowerMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\NoneMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\SnakeToCamelMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin;
use Popo\Schema\Mapper\SchemaMapper;

class SchemaMapperTest extends TestCase
{
    public function test_mapping_none(): void
    {
        $mapper = new SchemaMapper([
            (new NoneMappingPolicyPlugin()),
        ]);

        $key = $mapper->mapKeyName([NoneMappingPolicyPlugin::MAPPING_POLICY_NAME], 'idFoo');

        $this->assertEquals('idFoo', $key);
    }

    public function test_mapping_none_and_empty(): void
    {
        $mapper = new SchemaMapper([]);

        $key = $mapper->mapKeyName([NoneMappingPolicyPlugin::MAPPING_POLICY_NAME], 'idFoo');

        $this->assertEquals('idFoo', $key);
    }

    public function test_mapping_lower(): void
    {
        $mapper = new SchemaMapper([
            LowerMappingPolicyPlugin::MAPPING_POLICY_NAME => new LowerMappingPolicyPlugin(),

        ]);

        $key = $mapper->mapKeyName([LowerMappingPolicyPlugin::MAPPING_POLICY_NAME], 'idFoo');

        $this->assertEquals('idfoo', $key);
    }

    public function test_mapping_upper(): void
    {
        $mapper = new SchemaMapper([
            UpperMappingPolicyPlugin::MAPPING_POLICY_NAME => new UpperMappingPolicyPlugin(),
        ]);

        $key = $mapper->mapKeyName([UpperMappingPolicyPlugin::MAPPING_POLICY_NAME], 'idFoo');

        $this->assertEquals('IDFOO', $key);
    }

    public function test_mapping_camel_to_underscore(): void
    {
        $mapper = new SchemaMapper([
            CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME => new CamelToSnakeMappingPolicyPlugin(),
        ]);

        $key = $mapper->mapKeyName([CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME], 'idFoo');

        $this->assertEquals('id_foo', $key);
    }

    public function test_mapping_underscore_to_camel(): void
    {
        $mapper = new SchemaMapper([
            SnakeToCamelMappingPolicyPlugin::MAPPING_POLICY_NAME => new SnakeToCamelMappingPolicyPlugin(),
        ]);

        $key = $mapper->mapKeyName([SnakeToCamelMappingPolicyPlugin::MAPPING_POLICY_NAME], 'id_foo');

        $this->assertEquals('idFoo', $key);
    }

    public function test_mapping_camel_to_underscore_to_upper(): void
    {
        $mapper = new SchemaMapper([
            UpperMappingPolicyPlugin::MAPPING_POLICY_NAME => new UpperMappingPolicyPlugin(),
            CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME => new CamelToSnakeMappingPolicyPlugin(),
        ]);

        $key = $mapper->mapKeyName([
            CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
            UpperMappingPolicyPlugin::MAPPING_POLICY_NAME,

        ], 'idFoo');

        $this->assertEquals('ID_FOO', $key);
    }

    public function test_getSupportedMappingPolicies(): void
    {
        $mapper = new SchemaMapper([
            LowerMappingPolicyPlugin::MAPPING_POLICY_NAME => new LowerMappingPolicyPlugin(),
            UpperMappingPolicyPlugin::MAPPING_POLICY_NAME => new UpperMappingPolicyPlugin(),
            CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME => new CamelToSnakeMappingPolicyPlugin(),
        ]);

        $this->assertCount(3, $mapper->getSupportedMappingPolicies());
    }
}
