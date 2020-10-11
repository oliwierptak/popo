<?php

declare(strict_types = 1);

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;
use Tests\App\Generated\Dto\Foo;
use Tests\App\Generated\Dto\FooInterface;
use const Popo\APPLICATION_DIR;
use const Popo\TESTS_DIR;

class DtoGeneratedTest extends TestCase
{
    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    /**
     * @var \Popo\PopoFactoryInterface
     */
    protected $popoFactory;

    /**
     * @var string
     */
    protected $outputDirectory;

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = TESTS_DIR . 'fixtures/';
        $this->templateDirectory = APPLICATION_DIR . 'templates/';
        $this->outputDirectory = TESTS_DIR . 'App/Generated/';
    }

    public function test_constructor(): void
    {
        $foo = new Foo();

        $this->assertInstanceOf(FooInterface::class, $foo);
    }

    public function test_fromArrayToArray_defaults(): void
    {
        $value = [
            'fooId' => 'abc123',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
            'optionalData' => [
                ['id' => 123],
                ['id' => 456],
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
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
            'fooId' => 'abc123',
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
