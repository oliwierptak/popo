<?php

declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Popo\Configurator;
use Popo\PopoFacade;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;

class FacadeTest extends TestCase
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

    public function testGenerate(): void
    {
        $facade = new PopoFacade();

        $configurator = (new Configurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'popo/bundles/')
            ->setTemplateDirectory($this->templateDirectory)
            ->setOutputDirectory($this->outputDirectory . 'Configurator/')
            ->setNamespace('TestsPopoApp\App\Popo')
            ->setExtension('.php');

        $result = $facade->generate($configurator);
        $this->assertEquals(5, $result->getFileCount());
    }

    public function SKIP__testDirectoriesShouldExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Required schema directory does not exist under path:/');

        $facade = new PopoFacade();

        $configurator = (new Configurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'invalidPath')
            ->setTemplateDirectory($this->templateDirectory . 'invalidPath')
            ->setOutputDirectory($this->outputDirectory . 'invalidPath')
            ->setNamespace('TestsPopoApp\App\Popo')
            ->setExtension('.php');

        $facade->generatePopo($configurator);
    }

    public function SKIP__testGeneratePopo(): void
    {
        $facade = new PopoFacade();

        $configurator = (new Configurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'popo/bundles/')
            ->setTemplateDirectory($this->templateDirectory)
            ->setOutputDirectory($this->outputDirectory . 'Configurator/')
            ->setNamespace('TestsPopoApp\App\Popo')
            ->setExtension('.php');

        $numberOfFilesGenerated = $facade->generatePopo($configurator);

        $this->assertEquals(5, $numberOfFilesGenerated);
        $this->assertCreateDeep();
        $this->assertNoInterfaces();
    }

    protected function assertCreateDeep(): void
    {
        $fooPopo = new \App\Popo\Foo();
        $buzz = new \App\Popo\Buzz();
        $fooBar = new \App\Popo\FooBar();

        $this->assertInstanceOf(\App\Popo\Buzz::class, $buzz);
        $this->assertInstanceOf(\App\Popo\FooBar::class, $fooBar);

        $this->assertInstanceOf(\App\Popo\Buzz::class, $fooPopo->getBuzz());
        $this->assertFalse($fooPopo->hasBuzz());
    }

    protected function assertNoInterfaces(): void
    {
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/BuzzInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooBarInterface.php');
    }
}
