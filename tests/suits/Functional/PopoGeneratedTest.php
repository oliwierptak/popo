<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Configurator\Foo;
use App\Configurator\OptionalDataFoo;
use PHPUnit\Framework\TestCase;

class PopoGeneratedTest extends TestCase
{
    public function test_constructor(): void
    {
        $foo = new Foo();

        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function test_fromArrayToArray_defaults(): void
    {
        $value = [
            'fooId' => 123,
            'optionalData' => [
                ['id' => 456],
                ['id' => 789],
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => 123,
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Ipsum Option'],
                ['id' => 789, 'anOption' => 'Lorem Ipsum Option'],
            ],
            'buzz' => [
                'buzz' => 'Lorem ipsum',
            ],
            'fooBar' => [
                'fooBarId' => null,
                'value' => 'Lorem Ipsum',
                'buzzPropertyInFooBar' => null,
                'xyyPropertyInFooBar' => null,
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_fromArrayToArray_deep_values(): void
    {
        $value = [
            'fooId' => 123,
            'username' => 'JohnDoe',
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Option 1'],
                ['id' => 789, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1',
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => 123,
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Option 1'],
                ['id' => 789, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1',
            ],
            'fooBar' => [
                'fooBarId' => null,
                'value' => 'Lorem Ipsum',
                'buzzPropertyInFooBar' => null,
                'xyyPropertyInFooBar' => null,
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_has_fromArrayToArray_defaults(): void
    {
        $value = [
            'fooId' => '123',
            'optionalData' => [
                ['id' => 456],
                ['id' => 789],
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Ipsum Option'],
                ['id' => 789, 'anOption' => 'Lorem Ipsum Option'],
            ],
            'buzz' => [
                'buzz' => 'Lorem ipsum',
            ],
            'fooBar' => [
                'fooBarId' => null,
                'value' => 'Lorem Ipsum',
                'buzzPropertyInFooBar' => null,
                'xyyPropertyInFooBar' => null,
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());

        $this->assertTrue($foo->hasFooId());
        $this->assertTrue($foo->hasOptionalData());

        $this->assertFalse($foo->hasUsername());
        $this->assertFalse($foo->hasBuzz());
        $this->assertFalse($foo->hasIsLoggedIn());
        $this->assertFalse($foo->hasResetPassword());
        $this->assertFalse($foo->hasBuzz());
    }

    public function test_has_fromArrayToArray_deep_values(): void
    {
        $value = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Option 1'],
                ['id' => 789, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1',
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 456, 'anOption' => 'Lorem Option 1'],
                ['id' => 789, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1',
            ],
            'fooBar' => [
                'fooBarId' => null,
                'value' => 'Lorem Ipsum',
                'buzzPropertyInFooBar' => null,
                'xyyPropertyInFooBar' => null,
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());

        $this->assertTrue($foo->hasFooId());
        $this->assertTrue($foo->hasOptionalData());

        $this->assertTrue($foo->hasBuzz());
        $this->assertTrue($foo->hasIsLoggedIn());
        $this->assertTrue($foo->hasResetPassword());
        $this->assertTrue($foo->hasBuzz());
    }

    public function test_deep_popo(): void
    {
        $value = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
            ],
            'buzz' => [
                'buzz' => 'lorem buzz 1',
            ],
            'fooBar' => [
                'fooBarId' => 111,
                'value' => 'New Lorem Ipsum',
                'buzzPropertyInFooBar' => 'buzz',
                'xyyPropertyInFooBar' => 'xyy',
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $this->assertEquals('lorem buzz 1', $foo->getBuzz()->getBuzz());
        $this->assertEquals('New Lorem Ipsum', $foo->getFooBar()->getValue());
        $this->assertEquals('buzz', $foo->getFooBar()->getBuzzPropertyInFooBar());
        $this->assertEquals('xyy', $foo->getFooBar()->getXyyPropertyInFooBar());
    }

    public function test_add_collection_item(): void
    {
        $value = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
            ],
            'buzz' => [
                'buzz' => 'lorem buzz 1',
            ],
            'fooBar' => [
                'fooBarId' => 111,
                'value' => 'New Lorem Ipsum',
                'buzzPropertyInFooBar' => 'buzz',
                'xyyPropertyInFooBar' => 'xyy',
            ],
        ];

        $foo = (new Foo())->fromArray($value);
        $item = (new OptionalDataFoo())
            ->setId(987)
            ->setAnOption('abc');

        $foo->addOptionalDataItem($item);

        $expected = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 123, 'anOption' => 'Lorem Ipsum Option'],
                ['id' => 456, 'anOption' => 'Lorem Ipsum Option'],
                ['id' => 987, 'anOption' => 'abc'],
            ],
            'buzz' => [
                'buzz' => 'lorem buzz 1',
            ],
            'fooBar' => [
                'fooBarId' => 111,
                'value' => 'New Lorem Ipsum',
                'buzzPropertyInFooBar' => 'buzz',
                'xyyPropertyInFooBar' => 'xyy',
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_no_interfaces_should_be_generated(): void
    {
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/BuzzInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooBarInterface.php');
    }
}
