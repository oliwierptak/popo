<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use const POPO_TESTS_DIR;

/**
 * @group functional
 */
class GenerateTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        echo shell_exec(sprintf(
            'rm -rf %s',
            POPO_TESTS_DIR . 'App/Example/',
        ));
    }

    public function test_generate_from_file(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertTrue(true);
    }

    public function test_generate_from_file_readme_example(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertTrue(true);
    }

    public function test_generate_from_path(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/') //"bundle" comes form schemaPaths
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertTrue(true);
    }
}
