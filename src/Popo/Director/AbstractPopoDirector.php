<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfigurator;
use Popo\Builder\BuilderFactoryInterface;
use Popo\Generator\GeneratorInterface;
use Popo\Schema\SchemaFactoryInterface;

abstract class AbstractPopoDirector
{
    /**
     * @var \Popo\Builder\BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var \Popo\Schema\SchemaFactoryInterface
     */
    protected $schemaFactory;

    public function __construct(BuilderFactoryInterface $builderFactory, SchemaFactoryInterface $schemaFactory)
    {
        $this->builderFactory = $builderFactory;
        $this->schemaFactory = $schemaFactory;
    }

    protected function write(BuilderConfigurator $configurator, GeneratorInterface $generator): void
    {
        $this->assertConfiguration($configurator);

        $builderWriter = $this->builderFactory
            ->createBuilderWriter();

        $builderWriter->write($configurator, $generator);
    }

    protected function generate(BuilderConfigurator $configurator): void
    {
        $this->assertConfiguration($configurator);

        $generatorBuilder = $this->builderFactory
            ->createBuilder();
        $pluginContainer = $this->builderFactory
            ->createPluginContainer($configurator);

        $generator = $generatorBuilder->build($configurator, $pluginContainer);
        $this->write($configurator, $generator);
    }

    /**
     * @param \Popo\Builder\BuilderConfigurator $configurator
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function assertConfiguration(BuilderConfigurator $configurator): void
    {
        $schemaDirectory = $configurator->getSchemaDirectory();
        $outputDirectory = $configurator->getOutputDirectory();
        $templateDirectory = $configurator->getTemplateDirectory();

        $requiredPaths = [
            'schema' => $schemaDirectory,
            'output' => $outputDirectory,
            'template' => $templateDirectory,
        ];

        foreach ($requiredPaths as $type => $path) {
            if (!\is_dir($path)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Required %s directory does not exist under path: %s',
                    $type,
                    $path
                ));
            }
        }
    }
}
