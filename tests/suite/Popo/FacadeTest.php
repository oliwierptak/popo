<?php

declare(strict_types = 1);

namespace PopoTestSuite;

use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\AbstractGenerateTest;
use const Popo\POPO_TESTS_DIR;

/**
 * @group functional
 */
class FacadeTest extends AbstractGenerateTest
{

    public function test_generate_bundles(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaPathFilter('bundles')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Foo.php');
    }

    public function test_generate_from_path_popo(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_from_path_popo_and_config(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/popo.config.yml');

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_from_popo_file(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_readme_example(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Foo.php');
    }

    public function test_generate_example_with_namespace_root(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-namespace-root.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Foo.php');
    }

    public function test_inheritance(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-inheritance.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Inheritance/FooBar.php');
    }
}