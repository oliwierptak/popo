<?php

declare(strict_types = 1);

namespace PopoTestSuite\Command;

use PHPUnit\Framework\TestCase;
use Popo\Command\GenerateCommand;
use PopoTestSuiteHelper\SetupTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use const Popo\POPO_TESTS_DIR;

/**
 * @group functional
 */
class GenerateCommandTest extends TestCase
{
    use SetupTrait;

    protected function getCommandTester(): CommandTester
    {
        $command = new GenerateCommand();
        $application = new Application();
        $application->add($command);
        $command = $application->find(GenerateCommand::COMMAND_NAME);

        return new CommandTester($command);
    }

    public function test_command_params(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--namespace' => 'ExampleBundle\\AppRedefinedNamespace\\Example',
                '--namespaceRoot' => 'ExampleBundle\\',
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/,'.POPO_TESTS_DIR . 'fixtures/',
                '--outputPath' => POPO_TESTS_DIR,
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml',
                '--ignoreNonExistingSchemaFolder' => false,
                '--schemaFilenameMask' => '*.popo.yml'
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertFileExists(POPO_TESTS_DIR . 'AppRedefinedNamespace/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppRedefinedNamespace/Example/Foo.php');
    }

    public function test_generate_from_path(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/',
                '--outputPath' => POPO_TESTS_DIR,
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml',
                '--schemaFilenameMask' => '*.popo.yml',
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Foo.php');
    }

    public function test_generate_from_popo_file(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo.yml',
                '--outputPath' => POPO_TESTS_DIR,
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_readme_example(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo-readme.yml',
                '--outputPath' => POPO_TESTS_DIR,
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Foo.php');

    }

    public function test_generate_example_with_namespace_root(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo-namespace-root.yml',
                '--outputPath' => POPO_TESTS_DIR,
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Foo.php');
    }

    public function test_generate_should_throw_exception(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => '/foo/bar/fixtures/popos.yml',
            ]
        );

        $this->assertEquals(1, $result);
    }

    public function test_generate_should_not_throw_exception_when_ignoring_missing_schema(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popos.yml,'.POPO_TESTS_DIR . 'fixtures/popos.yml',
                '--ignoreNonExistingSchemaFolder' => true
            ]
        );

        $this->assertEquals(0, $result);
    }
}
