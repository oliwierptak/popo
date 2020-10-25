<?php declare(strict_types = 1);

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

    public function test_generate(): void
    {
        $facade = new PopoFacade();

        $configurator = (new Configurator())
            ->setConfigName('popo')
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory)
            ->setTemplateDirectory($this->templateDirectory)
            ->setOutputDirectory($this->outputDirectory)
            ->setNamespace('App\Configurator')
            ->setExtension('.php');
        $configurator
            ->getModelHelperConfigurator()
            ->setShowSummary(false);

        $result = $facade->generate($configurator);
        $this->assertEquals(5, $result->getFileCount());
    }

    public function test_directories_should_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Required schema directory does not exist under path:/');

        $facade = new PopoFacade();

        $configurator = (new Configurator())
            ->setConfigName('popo')
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'invalidPath')
            ->setTemplateDirectory($this->templateDirectory . 'invalidPath')
            ->setOutputDirectory($this->outputDirectory . 'invalidPath')
            ->setNamespace('App\Configurator')
            ->setExtension('.php');

        $facade->generate($configurator);
    }

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = POPO_TESTS_DIR . 'fixtures/popo/';
        $this->templateDirectory = POPO_APPLICATION_DIR . 'templates/';
        $this->outputDirectory = POPO_TESTS_DIR . 'App/Configurator/';
    }

    protected function no_interfaces_should_be_generated(): void
    {
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/BuzzInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/FooBarInterface.php');
        $this->assertFileNotExists(POPO_TESTS_DIR . 'App/Configurator/XyyInterface.php');
    }
}
