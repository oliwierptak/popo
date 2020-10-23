<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Popo\Foo;
use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;

class PopoReadmeTest extends TestCase
{
    protected string $schemaDirectory;
    protected string $templateDirectory;
    protected PopoFactory $popoFactory;
    protected string $outputDirectory;

    public function test_constructor(): void
    {
        $foo = new Foo();

        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function test_fromArrayToArray(): void
    {
        $value = [];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'title' => '',
            'bar' => [
                'value' => 'Lorem Ipsum Default Bar Value',
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());
    }

    public function test_fromArrayToArray_defaults(): void
    {
        $value = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $foo = (new Foo())->fromArray($value);

        $expected = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $this->assertEquals($expected, $foo->toArray());
    }

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = POPO_TESTS_DIR . 'fixtures/';
        $this->templateDirectory = POPO_APPLICATION_DIR . 'templates/';
        $this->outputDirectory = POPO_TESTS_DIR . 'App/Popo';
    }
}
