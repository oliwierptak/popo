<?php

declare(strict_types = 1);

namespace PopoTestsSuites;

use PHPUnit\Framework\TestCase;

abstract class AbstractPopoTest extends TestCase
{
    public static function setUpBeforeClass(): void
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
