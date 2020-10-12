<?php

declare(strict_types = 1);

namespace Tests\Functional\Writer;

use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\Builder\GeneratorBuilder;
use Popo\Builder\PluginContainer;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\FromArrayResultPlugin;
use Popo\Generator\Php\Plugin\Schema\ToArrayResultPlugin;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;
use Popo\Writer\File\FileWriter;
use Tests\App\Generated\BarStub;
use Tests\App\Generated\FooStub;

class FileWriterTest extends TestCase
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $schemaDataFoo;

    /**
     * @var array
     */
    protected $schemaDataBar;

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
        $this->outputDirectory = \Popo\TESTS_DIR . 'App/Generated/';
        $this->templateDirectory = \Popo\APPLICATION_DIR . 'templates/';
        $this->fooStubFilename = $this->outputDirectory . 'FooStub';
        $this->barStubFilename = $this->outputDirectory . 'BarStub';

        $this->schemaDataFoo = [
            'name' => 'Tests\App\Generated\FooStub',
            'schema' => [[
                'name' => 'id',
                'type' => 'int',
                'docblock' => 'Lorem Ipsum ID',
            ], [
                'name' => 'username',
                'type' => 'string',
                'default' => 'JohnDoe'
            ], [
                'name' => 'bar',
                'type' => '\Tests\App\Generated\BarStub'
            ], [
                'name' => 'barItems',
                'type' => 'array',
                'collectionItem' => '\Tests\App\Generated\BarStub',
                'singular' => 'barItem',
            ]]
        ];

        $this->schemaDataBar = [
            'name' => 'Tests\App\Generated\BarStub',
            'schema' => [[
                'name' => 'title',
                'type' => 'string',
                'default' => 'A Title',
                'docblock' => 'Lorem Ipsum Title',
            ], [
                'name' => 'price',
                'type' => 'int',
                'docblock' => 'Lorem Ipsum Price',
            ]]
        ];

        $this->data = [
            'id' => 123,
            'username' => 'FooBarBuzz',
            'bar' => ['price' => 999],
            'barItems' => [
                ['title' => 'Lorem Ipsum 1', 'price' => 2999],
                ['title' => 'Lorem Ipsum 2', 'price' => 7899],
            ],
        ];

        $this->expectedData = [
            'id' => 123,
            'username' => 'FooBarBuzz',
            'bar' => [
                'title' => 'A Title',
                'price' => 999,
            ],
            'barItems' => [[
                'title' => 'Lorem Ipsum 1',
                'price' => 2999,
            ], [
                'title' => 'Lorem Ipsum 2',
                'price' => 7899,
            ]],
        ];

        $this->writePopoInterface($this->schemaDataFoo, $this->fooStubFilename . 'Interface.php');
        $this->writePopo($this->schemaDataFoo, $this->fooStubFilename . '.php');

        $this->writePopoInterface($this->schemaDataBar, $this->barStubFilename . 'Interface.php');
        $this->writePopo($this->schemaDataBar, $this->barStubFilename . '.php');
    }

    protected function writePopoInterface(array $schemaData, string $filename): void
    {
        $popoFactory = new PopoFactory();
        $schema = $popoFactory->createReaderFactory()->createSchema($schemaData);

        $schemaBuilderConfigurator = (new SchemaConfigurator())
            ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
            ->setPropertyTemplateFilename('interface/php.interface.property.tpl')
            ->setCollectionTemplateFilename('interface/php.interface.collection.tpl');

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

        $pluginContainer = (new PluginContainer($propertyExplorer))
            ->registerSchemaClassPlugins([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->registerArrayableClassPlugins([
                FromArrayResultPlugin::PATTERN => FromArrayResultPlugin::class,
                ToArrayResultPlugin::PATTERN => ToArrayResultPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->registerPropertyClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ])
            ->registerCollectionClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        $generator = $generatorBuilder->build($configurator, $pluginContainer);
        $fileWriter = new FileWriter($generator);

        $fileWriter->write($filename, $schema);
    }

    protected function writePopo(array $schemaData, string $filename): void
    {
        $popoFactory = new PopoFactory();
        $schema = $popoFactory->createReaderFactory()->createSchema($schemaData);

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

        $pluginContainer = (new PluginContainer($propertyExplorer))
            ->registerSchemaClassPlugins([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->registerArrayableClassPlugins([
                FromArrayResultPlugin::PATTERN => FromArrayResultPlugin::class,
                ToArrayResultPlugin::PATTERN => ToArrayResultPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->registerPropertyClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ])
            ->registerCollectionClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        $schemaGenerator = $generatorBuilder->build($configurator, $pluginContainer);
        $fileWriter = new FileWriter($schemaGenerator);

        $fileWriter->write($filename, $schema);
    }

    public function testWritePopo(): void
    {
        $foo = new FooStub();
        $foo->fromArray($this->data);

        $this->assertSame($this->data['id'], $foo->getId());
        $this->assertSame($this->data['username'], $foo->getUsername());
        $this->assertSame($this->expectedData['barItems'][0], $foo->getBarItems()[0]->toArray());

        $this->assertSame($this->data['id'], $foo->requireId());
        $this->assertSame($this->data['username'], $foo->requireUsername());
        $this->assertSame($this->expectedData['bar'], $foo->requireBar()->toArray());
    }

    public function testToArray(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $data = $popo->toArray();

        $this->assertEquals($this->expectedData, $data);
    }

    public function testToArrayShouldConvertPopo(): void
    {
        $data = $this->data;
        $data['bar'] = (new BarStub())->fromArray($data['bar']);

        $popo = (new FooStub())->fromArray($data);
        $result = $popo->toArray();

        $expectedData = $this->expectedData;
        $expectedData['bar']['price'] = null;

        $this->assertEquals($expectedData, $result);
    }

    public function testFromArray(): void
    {
        $popo = new FooStub();

        $popo->fromArray($this->data);

        $this->assertEquals($this->expectedData, $popo->toArray());
    }

    public function testFromArrayShouldConvertPopo(): void
    {
        $data = $this->data;
        $data['bar'] = (new BarStub())->fromArray($this->data['bar']);

        $popo = new FooStub();

        $popo->fromArray($this->data);

        $this->assertEquals($this->expectedData, $popo->toArray());
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
        $popo->setBar($barStub);

        $this->assertSame($barStub, $popo->getbar());
    }

    public function testRequirePopoAsProperty(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $barStub = new BarStub();
        $popo->setBar($barStub);

        $this->assertSame($barStub, $popo->requireBar());
    }

    public function testRequirePropertyShouldReturnItsValue(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $this->assertSame($this->data['id'], $popo->requireId());
    }

    public function testRequirePropertyShouldThrowExceptionWithSetter(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "id" has not been set');

        $popo = new FooStub();
        $data = $this->data;

        $data['id'] = null;

        $popo->setId(null);

        $popo->requireId();
    }

    public function testRequirePropertyShouldThrowExceptionWithToArray(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Required value of "id" has not been set');

        $popo = new FooStub();
        $data = $this->data;

        $data['id'] = null;

        $popo->fromArray($data);

        $popo->requireId();
    }

    public function testCollectionAdd(): void
    {
        $popo = new FooStub();
        $popo->fromArray($this->data);

        $barStub = new BarStub();
        $popo->addBarItem($barStub);
        $popo->addBarItem($barStub);

        $this->assertCount(4, $popo->getBarItems());
    }

    public function testDefaultValue(): void
    {
        $popo = new FooStub();

        $this->assertSame('JohnDoe', $popo->getUsername());
    }

    public function testDefaultValueToArray(): void
    {
        $popo = new FooStub();

        $result = $popo->toArray();

        $this->assertSame('JohnDoe', $result['username']);
    }

    public function testDefaultValueFromArray(): void
    {
        $popo = (new FooStub())
            ->fromArray(['id' => 1]);

        $this->assertSame(1, $popo->getId());
        $this->assertSame('JohnDoe', $popo->getUsername());
    }
}
