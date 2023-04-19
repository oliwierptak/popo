<?php

declare(strict_types = 1);

namespace PopoTestSuite\Command;

use PHPUnit\Framework\TestCase;
use Popo\Command\ReportCommand;
use PopoTestSuiteHelper\SetupTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use const Popo\POPO_TESTS_DIR;

/**
 * @group functional
 */
class ReportCommandTest extends TestCase
{
    use SetupTrait;

    protected function getCommandTester(): CommandTester
    {
        $command = new ReportCommand();
        $application = new Application();
        $application->add($command);
        $command = $application->find(ReportCommand::COMMAND_NAME);

        return new CommandTester($command);
    }

    public function test_command_params(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/',
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml',
            ]
        );

        $this->assertEquals(0, $result);
    }

    public function test_generate_from_path(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/',
                '--schemaPathFilter' => 'bundles',
                '--schemaConfigFilename' => POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml',
            ]
        );

        $this->assertEquals(0, $result);
    }

    public function test_generate_from_popo_file(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo.yml',
            ]
        );

        $this->assertEquals(0, $result);
    }

    public function test_generate_readme_example(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo-readme.yml',
            ]
        );

        $this->assertEquals(0, $result);

    }

    public function test_generate_example_with_namespace_root(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popo-namespace-root.yml',
            ]
        );

        $this->assertEquals(0, $result);

    }

    public function test_generate_should_throw_exception(): void
    {
        $result = $this->getCommandTester()->execute(
            [
                'command' => ReportCommand::COMMAND_NAME,
                '--schemaPath' => POPO_TESTS_DIR . 'fixtures/popos.yml',
            ]
        );

        $this->assertEquals(1, $result);
    }
}
