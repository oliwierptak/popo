<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use Popo\Command\GenerateCommand;
use PopoTestsSuites\AbstractGenerateTest;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use const POPO_TESTS_DIR;

/**
 * @group functional
 */
class CommandTest extends AbstractGenerateTest
{
    protected function getCommandTester(): CommandTester
    {
        $command = new GenerateCommand();

        $application = new Application();
        $application->add($command);

        $command = $application->find(GenerateCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    public function test_command_params(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--namespace' => 'ExampleBundle\\AppRedefinedNamespace\\Example',
                '--namespaceRoot' => 'ExampleBundle\\',
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/',
                '--outputPath' => POPO_TESTS_DIR,
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml',
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertGenerateWithCommandParams();
    }

    public function test_generate_from_path(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/',
                '--outputPath' => POPO_TESTS_DIR,
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml',
            ]
        );

        $this->assertEquals(0, $result);
        $this->assertGenerateFromPath();
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
        $this->assertGenerateFromPopoFile();
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
        $this->assertGenerateFromReadmeExample();
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
        $this->assertGenerateWithNamespaceRoot();
    }

    public function test_generate_should_throw_exception(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popos.yml',
            ]
        );

        $this->assertEquals(1, $result);
    }

    public function test_build_invalid(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => GenerateCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo-invalid.yml',
                '--outputPath' => POPO_TESTS_DIR,
            ]
        );

        $this->assertEquals(1, $result);
    }
}
