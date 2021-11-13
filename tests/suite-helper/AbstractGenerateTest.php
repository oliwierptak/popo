<?php

declare(strict_types = 1);

namespace PopoTestSuiteHelper;

use PHPUnit\Framework\TestCase;

use const \Popo\POPO_TESTS_DIR;

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
}
