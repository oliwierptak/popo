<?php

declare(strict_types = 1);

namespace Tests\Popo\Schema;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;
use Popo\Schema\Reader\Property;
use Popo\Schema\SchemaConfigurator;
use Popo\Schema\Validator\Exception\NotBundleSchemaException;
use Popo\Schema\Validator\Exception\NotUniquePropertyException;

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
        $this->schemaDirectory = \Popo\TESTS_DIR . 'fixtures/dto/bundles/';
        $this->templateDirectory = \Popo\APPLICATION_DIR . 'templates/';
    }

    public function testMerge(): void
    {
        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = new SchemaConfigurator();

        $schemaFiles = $schemaBuilder->build($this->schemaDirectory, $configurator);
        $mergedSchema = $schemaMerger->merge($schemaFiles);

        $this->assertCount(4, $mergedSchema);

        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $fooSchemaFile
         */
        $fooSchemaFile = \current($mergedSchema);
        $this->assertCount(7, $fooSchemaFile->getSchema()->getSchema());
    }

    public function testMergeShouldCheckForUniquePropertyNames(): void
    {
        $this->expectException(NotUniquePropertyException::class);
        $this->expectExceptionMessageRegExp('/^The property: "fooId" is already defined in(.*)$/');

        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = new SchemaConfigurator();

        $schemaFiles = $schemaBuilder->build($this->schemaDirectory, $configurator);

        $last = \array_pop($schemaFiles);
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
        $this->expectExceptionMessageRegExp('/^Schema: "(.*)foo.schema.json" is not bundle schema$/');

        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();
        $schemaMerger = $schemaFactory->createSchemaMerger();
        $configurator = new SchemaConfigurator();

        $schemaFiles = $schemaBuilder->build($this->schemaDirectory, $configurator);

        $first = \array_shift($schemaFiles);
        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchemaFile
         */
        $bundleSchemaFile = $first['foo/schema']['Foo'];
        $bundleSchemaFile->setIsBundleSchema(false);
        $schemaFiles['foo.schema.json']['foo/schema']['Foo'] = $bundleSchemaFile;

        $schemaMerger->merge($schemaFiles);
    }
}
