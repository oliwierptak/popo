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
class ToArrayTest extends MappingPolicySelfShunt
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

    public function test_toArrayPolicy(): void
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
            $this->toArray()
        );
    }
}
