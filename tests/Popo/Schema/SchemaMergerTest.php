<?php

declare(strict_types = 1);

namespace Tests\Popo\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\PopoFactory;
use Popo\Schema\Reader\Property;
use Popo\Schema\SchemaConfigurator;
use Popo\Schema\Validator\Exception\NotBundleSchemaException;
use Popo\Schema\Validator\Exception\NotUniquePropertyException;
use function array_pop;
use function array_shift;
use function current;
use const Popo\APPLICATION_DIR;
use const Popo\TESTS_DIR;

class SchemaMergerTest extends TestCase
{
    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    protected function setUp(): void
    {
        $this->schemaDirectory = TESTS_DIR . 'fixtures/dto/bundles/';
        $this->templateDirectory = APPLICATION_DIR . 'templates/';
    }

    public function testMerge(): void
    {
        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory);

        $schemaFiles = $schemaBuilder->build($configurator);
        $mergedSchema = $schemaMerger->merge($schemaFiles);

        $this->assertCount(5, $mergedSchema);

        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $fooSchemaFile
         */
        $fooSchemaFile = current($mergedSchema);
        $this->assertCount(6, $fooSchemaFile->getSchema()->getSchema());
    }

    public function testMergeShouldCheckForUniquePropertyNames(): void
    {
        $this->expectException(NotUniquePropertyException::class);
        $this->expectExceptionMessageMatches('/^The property: "fooId" is already defined in(.*)$/');

        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory);

        $schemaFiles = $schemaBuilder->build($configurator);

        //pop twice for buzz
        array_pop($schemaFiles);
        $last = array_pop($schemaFiles);
        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchemaFile
         */
        $bundleSchemaFile = $last['buzz/schema']['Buzz'];
        $bundleSchemaFile->getSchema()->setName('Foo');
        $bundleSchemaFile->getschema()->setSchema([[
            Property::NAME => 'fooId',
            Property::TYPE => 'int',
        ]]);

        $schemaFiles['foo.schema.json']['buzz/schema'][$bundleSchemaFile->getSchema()->getName()] = $bundleSchemaFile;

        $schemaMerger->merge($schemaFiles);
    }

    public function testMergeShouldCheckForMainBundleSchema(): void
    {
        $this->expectException(NotBundleSchemaException::class);
        $this->expectExceptionMessageMatches('/^Schema: "(.*)foo.schema.json" is not bundle schema$/');

        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory);

        $schemaFiles = $schemaBuilder->build($configurator);

        $first = array_shift($schemaFiles);
        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchemaFile
         */
        $bundleSchemaFile = $first['foo/schema']['Foo'];
        $bundleSchemaFile->setIsBundleSchema(false);
        $schemaFiles['foo.schema.json']['foo/schema']['Foo'] = $bundleSchemaFile;

        $schemaMerger->merge($schemaFiles);
    }
}
