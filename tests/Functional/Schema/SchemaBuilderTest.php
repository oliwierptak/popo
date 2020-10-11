<?php

declare(strict_types = 1);

namespace Tests\Functional\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\PopoFactory;
use Popo\Schema\SchemaConfigurator;
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

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($this->schemaDirectory);

        $schemaFiles = $schemaBuilder->build($configurator);
        $this->assertCount(4, $schemaFiles);

        $this->assertCount(3, $schemaFiles['foo-bar.schema.json']);
    }
}
