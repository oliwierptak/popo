<?php

declare(strict_types = 1);

namespace Tests\Functional;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\PopoFacade;
use Popo\PopoFactory;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\SchemaConfigurator;
use const Popo\APPLICATION_DIR;
use const Popo\TESTS_DIR;

class PopoFacadeTest extends TestCase
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

    protected function buildSchema(array $schemaData): SchemaInterface
    {
        return $this->popoFactory
            ->createReaderFactory()->createSchema($schemaData);
    }

    public function testDirectoriesShouldExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Required schema directory does not exist under path:/');

        $facade = new PopoFacade();

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'invalidPath')
            ->setTemplateDirectory($this->templateDirectory . 'invalidPath')
            ->setOutputDirectory($this->outputDirectory . 'invalidPath')
            ->setNamespace('Tests\App\Generated\Popo')
            ->setExtension('.php');

        $facade->generatePopo($configurator);
    }

    public function testGeneratePopo(): void
    {
        $facade = new PopoFacade();

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory . 'popo/bundles/')
            ->setTemplateDirectory($this->templateDirectory)
            ->setOutputDirectory($this->outputDirectory . 'Popo/')
            ->setNamespace('Tests\App\Generated\Popo')
            ->setExtension('.php');

        $numberOfFilesGenerated = $facade->generatePopo($configurator);

        $this->assertEquals(5, $numberOfFilesGenerated);
        $this->assertCreateDeep();
        $this->assertNoInterfaces();
    }

    protected function assertCreateDeep(): void
    {
        $fooPopo = new \Tests\App\Generated\Popo\Foo();
        $buzz = new \Tests\App\Generated\Popo\Buzz();
        $fooBar = new \Tests\App\Generated\Popo\FooBar();

        $this->assertInstanceOf(\Tests\App\Generated\Popo\Buzz::class, $buzz);
        $this->assertInstanceOf(\Tests\App\Generated\Popo\FooBar::class, $fooBar);

        $this->assertInstanceOf(\Tests\App\Generated\Popo\Buzz::class, $fooPopo->getBuzz());
        $this->assertFalse($fooPopo->hasBuzz());
    }

    protected function assertNoInterfaces(): void
    {
        $this->assertFileNotExists(TESTS_DIR . 'App/Generated/Popo/BuzzInterface.php');
        $this->assertFileNotExists(TESTS_DIR . 'App/Generated/Popo/FooInterface.php');
        $this->assertFileNotExists(TESTS_DIR . 'App/Generated/Popo/FooBarInterface.php');
    }
}
