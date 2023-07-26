<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\Nested\Foo;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

class PopoNestedTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    public static function setUpBeforeClass(): void
    {
        self::removeGeneratedFiles();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-nested-from-array.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_from_nested_Array(): void
    {
        $foo = (new Foo())
            ->fromArray([
                'title' => 'Lorem Title',
                'bars' => [[
                    'value' => 'bar1',
                    'buzz' => [
                        'email' => 'email1',
                    ],
                ], [
                    'value' => 'bar2',
                    'buzz' => [
                        'email' => 'email2',
                    ],
                ], [
                    'value' => 'bar3',
                    'buzz' => [
                        'email' => 'email3',
                    ],
                ]],
            ]);

        $bar = $foo->getBars()[1];
        $this->assertEquals('Lorem Title', $foo->getTitle());
        $this->assertEquals('bar2', $bar->getValue());
        $this->assertEquals('email2', $bar->getBuzz()->getEmail());

        $this->assertEquals([
            'title' => 'Lorem Title',
            'bars' => [
                [
                    'value' => 'bar1',
                    'buzz' => [
                        'email' => 'email1',
                    ],
                ],
                [
                    'value' => 'bar2',
                    'buzz' => [
                        'email' => 'email2',
                    ],
                ],
                [
                    'value' => 'bar3',
                    'buzz' => [
                        'email' => 'email3',
                    ],
                ],
            ],
        ],
            $foo->toArray());
    }
}
