<?php

declare(strict_types = 1);

namespace Tests\Popo\Schema;

use PHPUnit\Framework\TestCase;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;
use function current;
use const Popo\APPLICATION_DIR;
use const Popo\TESTS_DIR;

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
        $this->schemaDirectory = TESTS_DIR . 'fixtures/dto/bundles/';
        $this->templateDirectory = APPLICATION_DIR . 'templates/';
    }

    public function testBuild(): void
    {
        $popoFactory = new PopoFactory();

        $schemaFactory = $popoFactory->createSchemaFactory();
        $schemaBuilder = $schemaFactory->createSchemaBuilder();

        $configurator = new SchemaConfigurator();
        $schemaFiles = $schemaBuilder->build($this->schemaDirectory, $configurator);
        $this->assertCount(4, $schemaFiles);

        /**
         * @var \Popo\Schema\Bundle\BundleSchemaInterface[] $fooSchemaFiles
         */
        $fooSchemaFiles = current($schemaFiles);
        $this->assertCount(2, $fooSchemaFiles);
    }
}
