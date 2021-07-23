<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use PHPUnit\Framework\TestCase;
use Popo\Schema\SchemaFile;

/**
 * @group unit
 */
class SchemaFileTest extends TestCase
{
    public function test(): void
    {
        $schemaFile = (new SchemaFile)
            ->setFilename(new \SplFileInfo(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml'))
            ->setSharedConfig(
                [
                    "Example" => [
                        "config" => [
                            "default" => [
                                "sharedExampleId" => 123,
                            ],
                            "property" => [
                                0 => [
                                    "name" => "sharedExampleId",
                                    "type" => "int",
                                ],
                            ],
                        ],
                        "property" => [],
                    ],
                    "AnotherExample" => [
                        "config" => [
                            "default" => [
                                "anotherTestSharedId" => 567,
                            ],
                            "property" => [
                                0 => [
                                    "name" => "anotherTestSharedId",
                                    "type" => "int",
                                ],
                            ],
                        ],
                        "property" => [],
                    ],
                ]
            )->setData(
                [
                    "data" => [
                        0 => [
                            "config" => [
                                "namespace" => "App\Example\Shared",
                                "outputPath" => "tests/",
                                "extend" => "App\AbstractExample::class",
                                "implement" => "App\ExampleInterface::class",
                                "comment" => "Popo Example. Auto-generated.",
                                "default" => [
                                    "title" => "Hakuna Matata",
                                ],
                                "property" => [
                                    0 => [
                                        "name" => "idForAll",
                                        "type" => "int",
                                        "default" => 0,
                                        "comment" => "This id is for all",
                                    ],
                                ],
                            ],
                            "property" => [
                                "Example" => [],
                                "AnotherExample" => [],
                            ],
                            "filename" => new \SplFileInfo(
                                '/www/oliwierptak/popo/tests/../tests/fixtures/bundles/shared.config.yml'
                            ),
                        ],
                    ],
                ]
            );

        $this->assertEquals(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml', $schemaFile->getFilename());
    }
}
