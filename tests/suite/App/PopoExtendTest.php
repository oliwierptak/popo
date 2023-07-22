<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\ExtendFromPopo\FooBarThird;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoExtendTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    protected PopoFacade $facade;

    public function setUp(): void
    {
        parent::setUp();

        self::removeGeneratedFiles();

        $this->facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-extend.yml')
            ->setOutputPath(POPO_TESTS_DIR);
        $this->facade->generate($configurator);
    }

    public function test_from_array(): void
    {
        $foo = (new FooBarThird())->fromArray([
            'first' => 1,
            'second' => 2,
            'third' => 3,
        ]);

        $this->assertEquals([
            'first' => 1,
            'second' => 2,
            'third' => 3,
        ], $foo->toArray());
    }
}
