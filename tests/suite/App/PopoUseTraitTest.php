<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\UseTrait\Foo;
use App\ExampleInterface;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoUseTraitTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    protected PopoFacade $facade;

    public function setUp(): void
    {
        parent::setUp();
        self::removeGeneratedFiles();

        $this->facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-trait-use.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $this->facade->generate($configurator);
    }

    public function test_trait(): void
    {
        $foo = new Foo();

        $this->assertEquals('Trait Example', $foo->getTraitExample());
    }

    public function test_use(): void
    {
        $foo = new Foo();

        $this->assertEquals(ExampleInterface::TEST_BUZZ, $foo->useExample());
    }
}
