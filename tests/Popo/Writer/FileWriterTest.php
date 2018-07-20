<?php

declare(strict_types = 1);

namespace Tests\Popo\Writer;

use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\Builder\GeneratorBuilder;
use Popo\Builder\PluginContainer;
use Popo\Generator\Php\Plugin\Property\Getter\GetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;
use Popo\Writer\File\FileWriter;
use Tests\App\Generated\Dto\BarStub;
use Tests\App\Generated\Dto\FooStub;

class FileWriterTest extends TestCase
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $schemaData;

    /**
     * @var string
     */
    protected $fooStubFilename;

    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    /**
     * @var string
     */
    protected $outputDirectory;

    /**
     * @var string
     */
    protected $barStubFilename;

    /**
     * @var array
     */
    protected $expectedData;

    protected function setUp(): void
    {
        $this->schemaDirectory = \Popo\TESTS_DIR . 'fixtures/dto/bundles/';
        $this->outputDirectory = \Popo\TESTS_DIR . 'App/Generated/Dto/';
        $this->templateDirectory = \Popo\APPLICATION_DIR . 'templates/';
        $this->fooStubFilename = $this->outputDirectory . 'FooStub';
        $this->barStubFilename = $this->outputDirectory . 'BarStub';

        $this->schemaData = [
            'name' => 'Tests\App\Generated\Dto\PopoStub',
            'schema' => [[
                'name' => 'id',
                'type' => 'int',
                'docblock' => 'Lorem Ipsum',
            ],[
                'name' => 'username',
                'type' => 'string',
                'default' => 'JohnDoe',
            ],[
                'name' => 'password',
                'type' => 'string',
            ],[
                'name' => 'isLoggedIn',
                'type' => 'bool',
            ],[
                'name' => 'resetPassword',
                'type' => 'bool',
                ],[
                'name' => 'context',
                'type' => 'mixed',
                ],[
                'name' => 'barStub',
                'type' => '\Tests\App\Generated\Dto\BarStub',
                ]],
        ];

        $this->writePopoInterface('Tests\App\Generated\Dto\BarStub', $this->barStubFilename . 'Interface.php');
        $this->writePopo('Tests\App\Generated\Dto\BarStub', $this->barStubFilename . '.php');

        $this->data = [
            'id' => 123,
            'username' => 'foo',
            'password' => 'bar',
            'isLoggedIn' => false,
            'resetPassword' => true,
            'context' => null,
            'barStub' => ['username' => 'bar'],
        ];

        $this->expectedData = [
            'id' => 123,
            'username' => 'foo',
            'password' => 'bar',
            'isLoggedIn' => false,
            'resetPassword' => true,
            'context' => null,
            'barStub' => [
                'id' => null,
                'username' => 'bar',
                'password' => null,
                'isLoggedIn' => null,
                'resetPassword' => null,
                'context' => null,
                'barStub' => null,
            ],
        ];
    }

    protected function writePopoInterface(string $name, string $filename): void
    {
        $popoFactory = new PopoFactory();
        $schema = $popoFactory->createReaderFactory()->createSchema($this->schemaData);
        $schema->setName($name);

        $schemaBuilderConfigurator = (new SchemaConfigurator())
            ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
            ->setPropertyTemplateFilename('interface/php.interface.property.tpl');

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator($schemaBuilderConfigurator)
            ->setTemplateDirectory($this->templateDirectory)
            ->setSchemaDirectory($this->schemaDirectory);

        $generatorBuilder = new GeneratorBuilder(
            $popoFactory->createLoaderFactory()->createContentLoader(),
            $popoFactory->createGeneratorFactory(),
            $popoFactory->createSchemaFactory()
        );

        $propertyExplorer = $popoFactory->createSchemaFactory()
            ->createPropertyExplorer();

        $pluginContainer = new PluginContainer($propertyExplorer);
        $pluginContainer->registerSchemaClassPlugins([
            DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
            DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
        ]);
        $pluginContainer->registerPropertyClassPlugins([
            GetMethodReturnTypeGeneratorPlugin::PATTERN => GetMethodReturnTypeGeneratorPlugin::class,
        ]);

        $generator = $generatorBuilder->build($configurator, $pluginContainer);
        $fileWriter = new FileWriter($generator);

        $fileWriter->write($filename, $schema);
    }

    protected function writePopo(string $name, string $filename): void
    {
        $popoFactory = new PopoFactory();
        $schema = $popoFactory->createReaderFactory()->createSchema($this->schemaData);
        $schema->setName($name);

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator((new SchemaConfigurator()))
            ->setTemplateDirectory($this->templateDirectory)
            ->setSchemaDirectory($this->schemaDirectory);

        $generatorBuilder = new GeneratorBuilder(
            $popoFactory->createLoaderFactory()->createContentLoader(),
            $popoFactory->createGeneratorFactory(),
            $popoFactory->createSchemaFactory()
        );

        $propertyExplorer = $popoFactory->createSchemaFactory()
            ->createPropertyExplorer();

        $pluginContainer = new PluginContainer($propertyExplorer);
        $pluginContainer->registerSchemaClassPlugins([
            DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
            DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
        ]);
        $pluginContainer->registerPropertyClassPlugins([
            GetMethodReturnTypeGeneratorPlugin::PATTERN => GetMethodReturnTypeGeneratorPlugin::class,
        ]);

        $schemaGenerator = $generatorBuilder->build($configurator, $pluginContainer);
        $fileWriter = new FileWriter($schemaGenerator);

        $fileWriter->write($filename, $schema);
    }

    public function testWritePopo(): void
    {
        $this->writePopoInterface('Tests\App\Generated\Dto\FooStub', $this->fooStubFilename . 'Interface.php');
        $this->writePopo('Tests\App\Generated\Dto\FooStub', $this->fooStubFilename . '.php');

        $generatedClass = new FooStub();
        $generatedClass->fromArray($this->data);

        $this->assertSame($this->data['id'], $generatedClass->getId());
        $this->assertSame($this->data['username'], $generatedClass->getUsername());
        $this->assertSame($this->data['password'], $generatedClass->getPassword());
        $this->assertSame($this->data['isLoggedIn'], $generatedClass->isLoggedIn());
        $this->assertSame($this->data['resetPassword'], $generatedClass->resetPassword());
        $this->assertSame($this->expectedData['barStub'], $generatedClass->getBarStub()->toArray());

        $this->assertSame($this->data['id'], $generatedClass->getId());
        $this->assertSame($this->data['username'], $generatedClass->requireUsername());
        $this->assertSame($this->data['password'], $generatedClass->requirePassword());
        $this->assertSame($this->data['isLoggedIn'], $generatedClass->requireIsLoggedIn());
        $this->assertSame($this->data['resetPassword'], $generatedClass->requireResetPassword());
        $this->assertSame($this->expectedData['barStub'], $generatedClass->requireBarStub()->toArray());
    }

    public function testToArray(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $data = $popo->toArray();

        $this->assertSame($this->expectedData, $data);
    }

    public function testToArrayShouldConvertPopo(): void
    {
        $data = $this->data;
        $data['barStub'] = (new BarStub())->fromArray($this->data['barStub']);
        $popo = (new FooStub())->fromArray($data);

        $data = $popo->toArray();

        $this->assertSame($this->expectedData, $data);
    }

    public function testFromArray(): void
    {
        $popo = new FooStub();

        $popo->fromArray($this->data);

        $this->assertSame($this->expectedData, $popo->toArray());
    }

    public function testFromArrayShouldConvertPopo(): void
    {
        $data = $this->data;
        $data['barStub'] = (new BarStub())->fromArray($this->data['barStub']);

        $popo = new FooStub();

        $popo->fromArray($this->data);

        $this->assertSame($this->expectedData, $popo->toArray());
    }

    public function testGetNullValue(): void
    {
        $popo = new FooStub();

        $data = $this->data;
        unset($data['id']);

        $popo->fromArray($data);

        $this->assertNull($popo->getId());
    }

    public function testSetNullValue(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $popo->setId(null);

        $data = $this->expectedData;
        $data['id'] = null;

        $this->assertSame($data, $popo->toArray());
    }

    public function testFromArrayShouldIgnoreUndefinedProperties(): void
    {
        $popo = new FooStub();

        $data = $this->data;
        $data['undefined'] = null;

        $popo->fromArray($data);

        $this->assertSame($this->expectedData, $popo->toArray());
    }

    public function testGetPopoAsProperty(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $barStub = new BarStub();
        $popo->setBarStub($barStub);

        $this->assertSame($barStub, $popo->getBarStub());
    }

    public function testRequirePopoAsProperty(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $barStub = new BarStub();
        $popo->setBarStub($barStub);

        $this->assertSame($barStub, $popo->requireBarStub());
    }

    public function testRequirePropertyShouldReturnItsValue(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $this->assertSame($this->data['id'], $popo->requireId());
    }

    public function testRequirePropertyShouldThrowException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "id" has not been set');

        $popo = new FooStub();
        $data = $this->data;

        $data['id'] = null;

        $popo->fromArray($data);

        $popo->requireId();
    }

    public function testDefaultValue(): void
    {
        $popo = new FooStub();

        $this->assertSame('JohnDoe', $popo->getUsername());
    }
}
