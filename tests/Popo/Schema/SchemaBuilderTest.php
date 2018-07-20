<?php

declare(strict_types = 1);

namespace Tests\Popo\Schema;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;

class SchemaBuilderTest extends TestCase
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

    public function testBuild(): void
    {
        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();

        $configurator = new SchemaConfigurator();
        $schemaFiles = $schemaBuilder->build($this->schemaDirectory, $configurator);
        $this->assertCount(3, $schemaFiles);

        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface $fooSchemaFile
         */
        $fooSchemaFiles = \current($schemaFiles);
        $this->assertCount(2, $fooSchemaFiles);
    }
}
