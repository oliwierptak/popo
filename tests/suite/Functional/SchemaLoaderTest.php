<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\PopoConfigurator;
use RuntimeException;
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
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);

        $this->assertNotEmpty($data[0]->getFileConfig());
        $this->assertNotEmpty($data[0]->getSchemaConfig());
    }

    public function test_load_popo_schema(): void
    {
        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);

        $this->assertNotEmpty($data[0]->getSchemaConfig());
        $this->assertNotEmpty($data[0]->getFileConfig());
    }

    public function test_load_path_with_shared_config(): void
    {
        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $data = $loader->load($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);

        $this->assertEmpty($data[0]->getSchemaConfig());
        $this->assertEmpty($data[1]->getSchemaConfig());
        $this->assertNotEmpty($data[2]->getSchemaConfig());
    }

    public function test_load_should_throw_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('@Specified path to POPO schema does not exist: "(.*)tests/fixtures/popos.yml"@i');

        $loader = new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader());

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popos.yml');

        $loader->load($configurator);
    }
}
