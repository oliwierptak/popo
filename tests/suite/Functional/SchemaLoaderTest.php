<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\PopoConfigurator;
use Symfony\Component\Finder\Finder;
use const POPO_TESTS_DIR;

/**
 * @group functional
 */
class SchemaLoaderTest extends TestCase
{
    public function test_load_shared_config(): void
    {
        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);

        $this->assertNotEmpty($data[0]->getSharedConfig());
    }

    public function test_load_popo_schema(): void
    {
        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);

        $this->assertEmpty($data[0]->getSharedConfig());
    }

    public function test_load_path_with_shared_config(): void
    {
        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);

        $this->assertEmpty($data[0]->getSharedConfig());
        $this->assertEmpty($data[1]->getSharedConfig());
        $this->assertNotEmpty($data[2]->getSharedConfig());
    }

    public function test_load_should_throw_exception(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Specified path to POPO schema does not exist: "/www/oliwierptak/popo/tests/../tests/fixtures/popos.yml"');

        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popos.yml');

        $data = $loader->load($configurator);
    }
}
