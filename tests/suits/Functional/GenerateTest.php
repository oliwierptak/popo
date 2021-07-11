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
    public const TEST_BUZZ = 123;

    public static function setUpBeforeClass(): void
    {
        echo shell_exec(sprintf(
            'rm %s',
            POPO_TESTS_DIR . 'App/Popo/Example/*.php'
        ));
    }

    public function test_generate(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-from-yaml/schema.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertTrue(true);
    }
}
