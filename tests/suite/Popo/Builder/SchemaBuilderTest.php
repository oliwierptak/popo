<?php

declare(strict_types = 1);

namespace PopoTestSuite\Builder;

use PHPUnit\Framework\TestCase;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\PopoConfigurator;
use Popo\Schema\ConfigMerger;
use Symfony\Component\Finder\Finder;
use const Popo\POPO_TESTS_DIR;

/**
 * @group functional
 */
class SchemaBuilderTest extends TestCase
{
    public function test_build_popo(): void
    {
        $builder = new SchemaBuilder(
            new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader()),
            new ConfigMerger()
        );

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml');

        $data = $builder->build($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);

        $this->assertNotEmpty($data['Example']);
        $this->assertNotEmpty($data['AnotherExample']);
    }

    public function test_build_project(): void
    {
        $builder = new SchemaBuilder(
            new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader()),
            new ConfigMerger()
        );

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $data = $builder->build($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);

        $this->assertNotEmpty($data['Example']);
        $this->assertNotEmpty($data['AnotherExample']);
    }
}
