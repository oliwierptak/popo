<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\PopoConfigurator;
use Symfony\Component\Finder\Finder;
use const POPO_TESTS_DIR;

/**
 * @group functional
 */
class SchemaBuilderTest extends TestCase
{
    public function test_build(): void
    {
        $builder = new SchemaBuilder(
            new SchemaLoader(new FileLocator(Finder::create()), new YamlLoader())
        );

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/bundles/')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/shared.config.yml');

        $data = $builder->build($configurator);

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);

        $this->assertNotEmpty($data['Example']);
        $this->assertNotEmpty($data['AnotherExample']);

        dump($data);
    }

}
