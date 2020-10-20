<?php

declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;
use TestsPopoApp\App\Generated\Popo\Foo;

class PopoGeneratedTest extends TestCase
{
    protected string $schemaDirectory;
    protected string $templateDirectory;
    protected PopoFactory $popoFactory;
    protected string $outputDirectory;

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = POPO_TESTS_DIR . 'fixtures/';
        $this->templateDirectory = POPO_APPLICATION_DIR . 'templates/';
        $this->outputDirectory = POPO_TESTS_DIR . 'App/Generated/';
    }

    public function test_constructor(): void
    {
        $foo = new Foo();

        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function test_fromArrayToArray_defaults(): void
    {
        $value = [
            'fooId' => '123',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
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
                ['id' => 123, 'anOption' => 'Lorem Option'],
                ['id' => 456, 'anOption' => 'Lorem Option'],
            ],
            'buzz' => [
                'buzz' => 'Lorem ipsum'
            ]
        ];

        $data = $foo->toArray();

        $this->assertEquals($expected, $data);
    }

    public function test_fromArrayToArray_deep_values(): void
    {
        $value = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'optionalData' => [
                ['id' => 123, 'anOption' => 'Lorem Option 1'],
                ['id' => 456, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1'
            ]
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 123, 'anOption' => 'Lorem Option 1'],
                ['id' => 456, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1'
            ]
        ];

        $data = $foo->toArray();

        $this->assertEquals($expected, $data);
    }

    public function test_has_fromArrayToArray_defaults(): void
    {
        $value = [
            'fooId' => '123',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
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
                ['id' => 123, 'anOption' => 'Lorem Option'],
                ['id' => 456, 'anOption' => 'Lorem Option'],
            ],
            'buzz' => [
                'buzz' => 'Lorem ipsum'
            ]
        ];

        $data = $foo->toArray();

        $this->assertEquals($expected, $data);

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
                ['id' => 123, 'anOption' => 'Lorem Option 1'],
                ['id' => 456, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1'
            ]
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => '123',
            'username' => 'JohnDoe',
            'password' => null,
            'isLoggedIn' => null,
            'resetPassword' => null,
            'optionalData' => [
                ['id' => 123, 'anOption' => 'Lorem Option 1'],
                ['id' => 456, 'anOption' => 'Lorem Option 2'],
            ],
            'buzz' => [
                'buzz' => 'Lorem new 1'
            ]
        ];

        $data = $foo->toArray();

        $this->assertEquals($expected, $data);

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
                'buzz' => 'lorem buzz 1'
            ]
        ];

        $foo = (new Foo())->fromArray($value);

        $this->assertEquals('lorem buzz 1', $foo->getBuzz()->getBuzz());
    }
}
