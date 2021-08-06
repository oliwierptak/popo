<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Unit;

use App\Example\Extend\FooBarFirst;
use App\Example\Extend\FooBarSecond;
use App\Example\Extend\FooBarThird;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestsSuites\AbstractPopoTest;

/**
 * @group unit
 */
class PopoExtendTest extends AbstractPopoTest
{
    protected PopoFacade $facade;

    public function setUp(): void
    {
        parent::setUp();

        $this->facade = new PopoFacade();
    }

    public function test_extend_schema_1(): void
    {
        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-extend-1.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $this->facade->generate($configurator);

        $foo = new FooBarFirst();

        $this->assertEquals(1, $foo->getIdForAll());
    }

    public function test_extend_schema_2(): void
    {
        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-extend-2.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $this->facade->generate($configurator);

        $foo = new FooBarSecond();

        $this->assertEquals(2, $foo->getIdForAll());
    }


    public function test_extend_schema_3(): void
    {
        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-extend-3.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $this->facade->generate($configurator);

        $foo = new FooBarThird();

        $this->assertEquals(3, $foo->getIdForAll());
    }
}
