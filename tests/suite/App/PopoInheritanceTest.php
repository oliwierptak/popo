<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\Inheritance\FooBar;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\AbstractPopoTest;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoInheritanceTest extends AbstractPopoTest
{
    protected PopoFacade $facade;

    public function setUp(): void
    {
        parent::setUp();

        $this->facade = new PopoFacade();
    }

    public function test_inheritance(): void
    {
        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-inheritance.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $this->facade->generate($configurator);

        $foo = new FooBar();

        $this->assertEquals(3, $foo->getIdForAll());
    }
}
