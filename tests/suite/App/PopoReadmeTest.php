<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\Readme\Foo;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoReadmeTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    public static function setUpBeforeClass(): void
    {
        self::removeGeneratedFiles();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_example_from_array(): void
    {
        $data = [
            'title' => 'A title',
            'bar' => [
                'title' => 'Bar lorem ipsum',
            ],
        ];

        $foo = (new Foo)->fromArray($data);

        $this->assertEquals('A title', $foo->getTitle());
        $this->assertEquals('Bar lorem ipsum', $foo->requireBar()->getTitle());
    }

    public function test_example_to_array(): void
    {
        $foo = (new Foo);
        $foo->requireBar()->setTitle('new value');
        $data = $foo->toArray();

        $expectedData = [
            'title' => null,
            'bar' => [
                'title' => 'new value',
            ],
        ];

        $this->assertEquals($expectedData, $data);
    }
}
