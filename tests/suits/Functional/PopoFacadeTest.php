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
class PopoFacadeTest extends TestCase
{
    public const TEST_BUZZ = 123;

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
