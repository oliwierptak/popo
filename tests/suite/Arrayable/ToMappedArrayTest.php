<?php

declare(strict_types = 1);

namespace PopoTestArrayable;

use App\ExampleInterface;
use Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\LowerMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\NoneMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\SnakeToCamelMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestArrayable\MappingPolicy\MappingPolicySelfShunt;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class ToMappedArrayTest extends MappingPolicySelfShunt
{
    use RemoveGeneratedClassesTrait;
    
    protected function setUp(): void
    {
        parent::setUp();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_toArrayPolicy_NONE(): void
    {
        $this->assertEquals(
            [
                'FOO_ID' => null,
                'title' => 'Example Foo Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                        'idForAll' => 20,
                        'idFromExampleSchema' => 20,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 40,
                    'idFromExampleSchema' => 20,
                ],
                'idForAll' => 30,
                'idFromExampleSchema' => 20,
            ],
            $this->toMappedArray(NoneMappingPolicyPlugin::MAPPING_POLICY_NAME)
        );
    }

    public function test_toArrayPolicy_TO_LOWER(): void
    {
        $this->assertEquals(
            [
                'foo_id' => null,
                'title' => 'Example Foo Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                        'idforall' => 20,
                        'idfromexampleschema' => 20,
                    ],
                    'buzzcollection' => [],
                    'idforall' => 40,
                    'idfromexampleschema' => 20,
                ],
                'idforall' => 30,
                'idfromexampleschema' => 20,
            ],
            $this->toMappedArray(LowerMappingPolicyPlugin::MAPPING_POLICY_NAME)
        );
    }

    public function test_toArrayPolicy_TO_UPPER(): void
    {
        $this->assertEquals(
            [
                'FOO_ID' => null,
                'TITLE' => 'Example Foo Hakuna Matata',
                'VALUE' => ExampleInterface::TEST_BUZZ,
                'BAR' => [
                    'TITLE' => 'Lorem Ipsum',
                    'BUZZ' => [
                        'VALUE' => 'Buzzzzz',
                        'IDFORALL' => 20,
                        'IDFROMEXAMPLESCHEMA' => 20,
                    ],
                    'BUZZCOLLECTION' => [],
                    'IDFORALL' => 40,
                    'IDFROMEXAMPLESCHEMA' => 20,
                ],
                'IDFORALL' => 30,
                'IDFROMEXAMPLESCHEMA' => 20,
            ],
            $this->toMappedArray(UpperMappingPolicyPlugin::MAPPING_POLICY_NAME)
        );
    }

    public function test_toArrayPolicy_CAMEL_TO_SNAKE_TO_LOWERCASE(): void
    {
        $this->assertEquals(
            [
                'foo_id' => null,
                'title' => 'Example Foo Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                        'id_for_all' => 20,
                        'id_from_example_schema' => 20,
                    ],
                    'buzz_collection' => [],
                    'id_for_all' => 40,
                    'id_from_example_schema' => 20,
                ],
                'id_for_all' => 30,
                'id_from_example_schema' => 20,
            ],
            $this->toMappedArray(
                CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
                LowerMappingPolicyPlugin::MAPPING_POLICY_NAME,
            )
        );
    }

    public function test_toArrayPolicy_SNAKE_UPPERCASED(): void
    {
        $this->assertEquals(
            [
                'FOO_ID' => null,
                'TITLE' => 'Example Foo Hakuna Matata',
                'VALUE' => ExampleInterface::TEST_BUZZ,
                'BAR' => [
                    'TITLE' => 'Lorem Ipsum',
                    'BUZZ' => [
                        'VALUE' => 'Buzzzzz',
                        'ID_FOR_ALL' => 20,
                        'ID_FROM_EXAMPLE_SCHEMA' => 20,
                    ],
                    'BUZZ_COLLECTION' => [],
                    'ID_FOR_ALL' => 40,
                    'ID_FROM_EXAMPLE_SCHEMA' => 20,
                ],
                'ID_FOR_ALL' => 30,
                'ID_FROM_EXAMPLE_SCHEMA' => 20,
            ],
            $this->toMappedArray(
                CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
                UpperMappingPolicyPlugin::MAPPING_POLICY_NAME,
            )
        );
    }

    public function test_toArrayPolicy_CAMEL_TO_SNAKE_TO_CAMEL(): void
    {
        $this->assertEquals(
            [
                'fooId' => null,
                'title' => 'Example Foo Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                        'idForAll' => 20,
                        'idFromExampleSchema' => 20,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 40,
                    'idFromExampleSchema' => 20,
                ],
                'idForAll' => 30,
                'idFromExampleSchema' => 20,
            ],
            $this->toMappedArray(
                CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
                SnakeToCamelMappingPolicyPlugin::MAPPING_POLICY_NAME,
            )
        );
    }

    public function test_toArrayPolicy_MIXED(): void
    {
        $this->assertEquals(
            [
                'FOO_ID' => null,
                'TITLE' => 'Example Foo Hakuna Matata',
                'VALUE' => ExampleInterface::TEST_BUZZ,
                'BAR' => [
                    'TITLE' => 'Lorem Ipsum',
                    'BUZZ' => [
                        'VALUE' => 'Buzzzzz',
                        'ID_FOR_ALL' => 20,
                        'ID_FROM_EXAMPLE_SCHEMA' => 20,
                    ],
                    'BUZZ_COLLECTION' => [],
                    'ID_FOR_ALL' => 40,
                    'ID_FROM_EXAMPLE_SCHEMA' => 20,
                ],
                'ID_FOR_ALL' => 30,
                'ID_FROM_EXAMPLE_SCHEMA' => 20,
            ],
            $this->toMappedArray(
                CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
                LowerMappingPolicyPlugin::MAPPING_POLICY_NAME,
                SnakeToCamelMappingPolicyPlugin::MAPPING_POLICY_NAME,
                CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME,
                UpperMappingPolicyPlugin::MAPPING_POLICY_NAME,
            )
        );
    }
}
