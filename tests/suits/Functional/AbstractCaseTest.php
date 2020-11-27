<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;

abstract class AbstractCaseTest extends TestCase
{
    protected string $schemaDirectory;

    protected string $templateDirectory;

    protected PopoFactory $popoFactory;

    protected string $outputDirectory;

    public function test_constructor(): void
    {
        $popo = $this->getPopoToTest();

        $this->assertInstanceOf($this->getPopoToTestClassName(), $popo);
    }

    abstract protected function getPopoToTest(): object;

    abstract protected function getPopoToTestClassName(): string;

    public function test_fromArrayToArray(): void
    {
        $value = [];

        $popo = $this->getPopoToTest()->fromArray($value);

        $expected = [
            'title' => '',
            'bar' => [
                'value' => 'Lorem Ipsum Default Bar Value',
            ],
        ];

        $this->assertEquals($expected, $popo->toArray());
    }

    public function test_fromArrayToArray_defaults(): void
    {
        $value = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $popo = $this->getPopoToTest()->fromArray($value);

        $expected = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $this->assertEquals($expected, $popo->toArray());
    }

    public function test_fromArray_overrides_state(): void
    {
        $value = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $popo = $this->getPopoToTest();
        $popo->setTitle('foo bar title that will be overwritten');

        $popo = $this->getPopoToTest()->fromArray($value);

        $expected = [
            'title' => 'A title',
            'bar' => [
                'value' => 'Bar lorem ipsum',
            ],
        ];

        $this->assertEquals($expected, $popo->toArray());
    }

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = POPO_TESTS_DIR . 'fixtures/';
        $this->templateDirectory = POPO_APPLICATION_DIR . 'templates/';
        $this->outputDirectory = POPO_TESTS_DIR . 'App/Popo';
    }
}
