<?php

declare(strict_types = 1);

namespace PopoTestArrayable;

use App\ExampleInterface;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestArrayable\MappingPolicy\MappingPolicySelfShunt;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class FromMappedArrayTest extends MappingPolicySelfShunt
{
    use RemoveGeneratedClassesTrait;

    public static function setUpBeforeClass(): void
    {
        self::removeGeneratedFiles();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_fromMappedArray_with_policy(): void
    {
        $this->fromMappedArray(
            [
                'foo_id' => 123,
                'title' => 'The Title',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum Title',
                    'buzz' => [
                        'value' => 'Buzzzzz Value',
                        'id_for_all' => 987,
                        'id_from_example_schema' => 567,
                    ],
                    'buzz_collection' => [],
                    'id_for_all' => 99,
                    'id_from_example_schema' => 20,
                ],
                'id_for_all' => 1,
                'id_from_example_schema' => 2,
            ],
            'camel-to-snake', 'lower'
        );

        $this->assertEquals(
            [
                'FOO_ID' => 123,
                'title' => 'The Title',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum Title',
                    'buzz' => [
                        'value' => 'Buzzzzz Value',
                        'idForAll' => 987,
                        'idFromExampleSchema' => 567,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 99,
                    'idFromExampleSchema' => 20,
                ],
                'idForAll' => 1,
                'idFromExampleSchema' => 2,
            ],
            $this->toArray()
        );
    }

    public function test_toArrayPolicy_should_ignore_wrong_keys(): void
    {
        $this->fromArray(
            [
                'foo_id' => 123,
                'title' => 'The Title',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum Title',
                    'buzz' => [
                        'value' => 'Buzzzzz Value',
                        'idForAll' => 987,
                        'idFromExampleSchema' => 567,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 99,
                    'idFromExampleSchema' => 20,
                ],
                'idForAll' => 1,
                'idFromExampleSchema' => 2,
            ],
        );

        $this->assertEquals(
            [
                'FOO_ID' => null,
                'title' => 'The Title',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum Title',
                    'buzz' => [
                        'value' => 'Buzzzzz Value',
                        'idForAll' => 987,
                        'idFromExampleSchema' => 567,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 99,
                    'idFromExampleSchema' => 20,
                ],
                'idForAll' => 1,
                'idFromExampleSchema' => 2,
            ],
            $this->toArray()
        );
    }
}
