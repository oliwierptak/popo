<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\PopoFacade;

class PopoFacadeTest extends TestCase
{
    public const TEST_BUZZ = 123;

    public function test_generate(): void
    {
        $facade = new PopoFacade();

        $configurationFiles = [
            \POPO_TESTS_DIR . 'fixtures/popo-from-yaml/schema.yml'
        ];

        $facade->generate($configurationFiles);

        $this->assertTrue(true);
    }
}
