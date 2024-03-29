<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\DateTime\Bar;
use App\Example\DateTime\Foo;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use UnexpectedValueException;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class DateTimeTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    public static function setUpBeforeClass(): void
    {
        self::removeGeneratedFiles();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-datetime.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_datetime(): void
    {
        $foo = (new Foo())->setTitle('Example Foo Hakuna Matata');

        $modified = $foo->requireBar()->requireModified();

        $this->assertEquals(1641046937, $modified->getTimestamp());
    }

    public function test_timezone(): void
    {
        $foo = (new Foo())->setTitle('Example Foo Hakuna Matata');

        $modified = $foo->requireBar()->requireModified();

        $this->assertEquals('Europe/Paris', $modified->getTimezone()->getName());
    }

    public function test_timezone_change(): void
    {
        $result = ini_set('date.timezone', 'UTC');
        $foo = (new Foo())->setTitle('Example Foo Hakuna Matata');

        $modified = $foo->requireBar()->requireModified();

        $this->assertEquals('Europe/Paris', $modified->getTimezone()->getName());
        $this->assertEquals(1641046937, $modified->getTimestamp());

        ini_set('date.timezone', $result);
    }

    public function test_toArray_update_datetime(): void
    {
        $timestamp = time() - 2000;

        $expectedModified = new DateTime("@$timestamp");
        $expectedModified->setTimezone(new DateTimeZone('Europe/Paris'));

        $foo = new Foo;

        $foo
            ->setTitle('Example Foo Hakuna Matata')
            ->requireBar()
            ->requireModified()
            ->setTimestamp($timestamp);

        $this->assertEquals(
            [
                'title' => 'Example Foo Hakuna Matata',
                'bar' => [
                    'created' => $foo->getBar()->requireCreated()->format('D, d M y H:i:s O'),
                    'modified' => $expectedModified->format('D, d M y H:i:s O'),
                ],
            ],
            $foo->toArray()
        );
    }

    public function test_toArray(): void
    {
        $foo = (new Foo())->setTitle('Example Foo Hakuna Matata');

        $this->assertEquals(
            [
                'title' => 'Example Foo Hakuna Matata',
                'bar' => [
                    'created' => $foo->requireBar()->requireCreated()->format('D, d M y H:i:s O'),
                    'modified' => 'Sat, 01 Jan 22 15:22:17 +0100',
                ],
            ],
            $foo->toArray()
        );
    }

    public function test_fromArray_update_timestamp(): void
    {
        $timestamp = time() - 2000;
        $expectedModified = new DateTime();
        $expectedModified->setTimestamp($timestamp);
        $expectedModified->setTimezone(new DateTimeZone('Europe/Paris'));

        $foo = new Foo;
        $foo
            ->setTitle('Example Foo Hakuna Matata')
            ->requireBar()
            ->setModified($expectedModified);

        $expected = [
            'title' => 'Example Foo Hakuna Matata',
            'bar' => [
                'created' => $foo->requireBar()->requireCreated()->format('D, d M y H:i:s O'),
                'modified' => $expectedModified->format('D, d M y H:i:s O'),
            ],
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_fromArray(): void
    {
        $foo = (new Foo);

        $expected = [
            'title' => 'Example Foo Hakuna Matata',
            'bar' => [
                'created' => $foo->requireBar()->requireCreated()->format('D, d M y H:i:s O'),
                'modified' => 'Sat, 01 Jan 22 15:22:17 +0100',
            ],
        ];

        $foo->fromArray($expected);

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_is_new(): void
    {
        $foo = (new Foo);

        $this->assertTrue($foo->isNew());

        $foo->setTitle('Lorem ipsum');
        $this->assertFalse($foo->isNew());
    }

    public function test_require_default(): void
    {
        $foo = (new Foo)->setTitle('Example Foo Hakuna Matata');

        $this->assertEquals('Example Foo Hakuna Matata', $foo->getTitle());
        $this->assertInstanceOf(DateTime::class, $foo->requireBar()->requireModified());
    }

    public function test_require_title(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "title" has not been set');

        $foo = (new Foo)->setTitle(null);

        $foo->requireTitle();
    }

    public function test_require(): void
    {
        $foo = (new Foo)->setTitle('Example Foo Hakuna Matata');

        $this->assertNull($foo->getBar());
        $this->assertInstanceOf(Bar::class, $foo->requireBar());
        $this->assertEquals('Example Foo Hakuna Matata', $foo->requireTitle());
    }

    public function test_require_all_exception(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "title" has not been set');

        $foo = (new Foo)->setTitle(null);

        $foo->requireAll();
    }

    public function test_require_all(): void
    {
        $foo = (new Foo)->setTitle('abc');

        $foo->requireAll();

        $this->assertEquals('abc', $foo->getTitle());
    }

    public function test_has(): void
    {
        $foo = (new Foo)->setTitle('abc');

        $this->assertTrue($foo->hasTitle());

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());

        $foo->setTitle('abc');
        $this->assertTrue($foo->hasTitle());
    }

    public function test_modified_properties(): void
    {
        $foo = (new Foo);

        $this->assertFalse($foo->hasTitle());
        $this->assertEmpty($foo->listModifiedProperties());

        $foo = (new Foo)->setTitle('abc');

        $foo->setTitle(null);
        $this->assertFalse($foo->hasTitle());
        $this->assertEquals(['title'], $foo->listModifiedProperties());
    }
}
