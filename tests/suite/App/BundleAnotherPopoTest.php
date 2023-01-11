<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\Shared\AnotherFoo;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\AbstractPopoTest;
use UnexpectedValueException;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class BundleAnotherPopoTest extends AbstractPopoTest
{

    protected function setUp(): void
    {
        parent::setUp();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaPathFilter('bundles')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $facade->generate($configurator);
    }

    public function test_toArray(): void
    {
        $foo = (new AnotherFoo());

        $this->assertEquals(
            [
                'idForAll' => 0,
                'anotherExampleSharedId' => 567,
                'description' => 'Another Lorem Ipsum',
                'idForAnotherExample' => 999,
                'title' => 'Hakuna Matata',
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $foo = (new AnotherFoo);

        $expected = [
            'idForAll' => 0,
            'anotherExampleSharedId' => 567,
            'description' => 'Another Lorem Ipsum',
            'idForAnotherExample' => 999,
            'title' => 'Hakuna Matata',
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new AnotherFoo);

        $this->assertTrue($foo->isNew());

        $foo->setTitle('abc');
        $this->assertFalse($foo->isNew());
    }

    public function test_require_default(): void
    {
        $foo = (new AnotherFoo);

        $this->assertEquals('Hakuna Matata', $foo->getTitle());
        $this->assertEquals(567, $foo->getAnotherExampleSharedId());
        $this->assertEquals(0, $foo->getIdForAll());
    }

    public function test_require_fooId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "anotherExampleSharedId" has not been set');

        $foo = (new AnotherFoo)->setAnotherExampleSharedId(null);

        $foo->requireAnotherExampleSharedId();
    }

    public function test_require(): void
    {
        $foo = (new AnotherFoo);

        $this->assertEquals('Hakuna Matata', $foo->requireTitle());
        $this->assertEquals(567, $foo->requireAnotherExampleSharedId());
        $this->assertEquals(0, $foo->requireIdForAll());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "anotherExampleSharedId" has not been set');

        $foo = (new AnotherFoo)->setAnotherExampleSharedId(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new AnotherFoo)
            ->setIdForAll(1);

        $foo->requireAll();

        $this->assertEquals(1, $foo->getIdForAll());
    }

    public function test_has(): void
    {
        $foo = (new AnotherFoo);

        $this->assertTrue($foo->hasTitle());

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());
    }

    public function test_modified_properties(): void
    {
        $foo = (new AnotherFoo);

        $this->assertTrue($foo->hasTitle());
        $this->assertEmpty($foo->listModifiedProperties());

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());
        $this->assertEquals(['title'], $foo->listModifiedProperties());
    }
}
