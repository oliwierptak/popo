<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestsSuites\AbstractGenerateTest;
use RuntimeException;
use const POPO_TESTS_DIR;

/**
 * @group functional
 */
class FacadeTest extends AbstractGenerateTest
{

    public function test_generate_from_path(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaPathFilter('bundles')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml');

        $facade->generate($configurator);

        $this->assertGenerateFromPath();
    }

    public function test_generate_from_popo_file(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertGenerateFromPopoFile();
    }

    public function test_generate_readme_example(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertGenerateFromReadmeExample();
    }

    public function test_generate_example_with_namespace_root(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-namespace-root.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertGenerateWithNamespaceRoot();
    }

    public function test_generate_should_throw_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('@Specified path to POPO schema does not exist: "(.*)tests/fixtures/popos.yml"@i');

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popos.yml');

        $facade->generate($configurator);
    }

    public function test_build_invalid(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Property with name "idForAll" is already defined and cannot be used for "Example::FooBar" in "/www/oliwierptak/popo/tests/../tests/fixtures/popo-invalid.yml"');

        $facade = new PopoFacade();


        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-invalid.yml');

        $facade->generate($configurator);
    }
}
