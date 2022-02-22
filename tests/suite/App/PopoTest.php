<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\Popo\Bar;
use App\Example\Popo\Buzz\Buzz;
use App\Example\Popo\Foo;
use App\ExampleInterface;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\AbstractPopoTest;
use UnexpectedValueException;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoTest extends AbstractPopoTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_toArray(): void
    {
        $foo = (new Foo());

        $this->assertEquals(
            [
                'fooId' => null,
                'title' => 'Example Foo Hakuna Matata',
                'value' => ExampleInterface::TEST_BUZZ,
                'bar' => [
                    'title' => 'Lorem Ipsum',
                    'buzz' => [
                        'value' => 'Buzzzzz',
                        'idForAll' => 20,
                        'idFromExampleSchema' => 20,
                    ],
                    'buzzCollection' => [],
                    'idForAll' => 40,
                    'idFromExampleSchema' => 20,
                ],
                'isTest' => true,
                'isTestWithoutDefault' => false,
                'idForAll' => 30,
                'idFromExampleSchema' => 20,
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $foo = (new Foo);

        $expected = [
            'fooId' => null,
            'title' => 'Example Foo Hakuna Matata',
            'value' => ExampleInterface::TEST_BUZZ,
            'bar' => [
                'title' => 'Lorem Ipsum',
                'buzz' => [
                    'value' => 'Buzzzzz',
                    'idForAll' => 20,
                    'idFromExampleSchema' => 20,
                ],
                'buzzCollection' => [],
                'idForAll' => 40,
                'idFromExampleSchema' => 20,
            ],
            'isTest' => true,
            'isTestWithoutDefault' => false,
            'idForAll' => 30,
            'idFromExampleSchema' => 20,
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->isNew());

        $foo->setValue(1);
        $this->assertFalse($foo->isNew());
    }

    public function test_require_default(): void
    {
        $foo = (new Foo);

        $this->assertEquals('Example Foo Hakuna Matata', $foo->getTitle());
        $this->assertEquals('Buzzzzz', $foo->requireBar()->requireBuzz()->getValue());
    }

    public function test_require_fooId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "fooId" has not been set');

        $foo = (new Foo);

        $foo->requireFooId();
    }

    public function test_require(): void
    {
        $foo = (new Foo);

        $this->assertNull($foo->getBar());
        $this->assertInstanceOf(Bar::class, $foo->requireBar());
        $this->assertEquals('Example Foo Hakuna Matata', $foo->requireTitle());
        $this->assertEquals(ExampleInterface::TEST_BUZZ, $foo->requireValue());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "fooId" has not been set');
        $this->expectExceptionMessage('Required value of "value" has not been set');

        $foo = (new Foo)->setValue(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new Foo)
            ->setFooId(1);

        $foo->requireAll();

        $this->assertEquals(1, $foo->getFooId());
    }

    public function test_require_all_collection_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "buzzCollection" has not been set');

        $bar = (new Bar());

        $bar->requireBuzzCollection();
    }

    public function test_require_all_collection(): void
    {
        $bar = (new Bar)->setBuzzCollection([
            new Buzz,
        ]);

        $this->assertNotEmpty($bar->getBuzzCollection());
    }

    public function test_has(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->hasValue());

        $foo->setValue(null);
        $this->assertFalse($foo->hasValue());
    }

    public function test_has_collection(): void
    {
        $foo = (new Foo);

        $this->assertFalse($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->setBuzzCollection([new Buzz]);
        $this->assertTrue($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->setBuzzCollection([]);
        $this->assertFalse($foo->requireBar()->hasBuzzCollection());
    }

    public function test_add_collection_item(): void
    {
        $foo = (new Foo);

        $this->assertFalse($foo->requireBar()->hasBuzzCollection());

        $foo->requireBar()->addBuzz(new Buzz());
        $this->assertTrue($foo->requireBar()->hasBuzzCollection());
    }

    public function test_modified_properties(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->hasTitle());
        $this->assertEmpty($foo->listModifiedProperties());

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());
        $this->assertEquals(['title'], $foo->listModifiedProperties());
    }

    public function test_default_bool_value(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->isTest());
        $this->assertFalse($foo->isTestWithoutDefault());
    }

    public function test_default_array_value(): void
    {
        $bar = (new Bar);

        $this->assertIsArray($bar->getBuzzCollection());
        $this->assertEmpty($bar->getBuzzCollection());
    }
}
