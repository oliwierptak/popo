<?php

declare(strict_types = 1);

namespace PopoTestsSuites;

use PHPUnit\Framework\TestCase;

abstract class AbstractGenerateTest extends TestCase
{
    protected function setup(): void
    {
        echo shell_exec(sprintf(
            'rm -rf %s',
            POPO_TESTS_DIR . 'App/Example/',
        ));

        echo shell_exec(sprintf(
            'rm -rf %s',
            POPO_TESTS_DIR . 'AppRedefinedNamespace/Example/',
        ));

        echo shell_exec(sprintf(
            'rm -rf %s',
            POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/',
        ));
    }

    protected function assertGenerateFromPath(): void
    {
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Fizz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/AnotherBar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Foo.php');
    }

    protected function assertGenerateFromPopoFile(): void
    {
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Fizz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    protected function assertGenerateFromReadmeExample(): void
    {
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Foo.php');
    }

    protected function assertGenerateWithNamespaceRoot(): void
    {
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Foo.php');
    }

    protected function assertGenerateWithCommandParams(): void
    {
        $this->assertFileExists(POPO_TESTS_DIR . 'AppRedefinedNamespace/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppRedefinedNamespace/Example/Foo.php');
    }
}
