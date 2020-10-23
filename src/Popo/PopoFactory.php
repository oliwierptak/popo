<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderFactory;
use Popo\Finder\FinderFactory;
use Popo\Generator\GeneratorFactory;
use Popo\Model\ConfiguratorProvider;
use Popo\Model\Helper\ProgressIndicator;
use Popo\Model\Popo;
use Popo\Schema\Bundle\BundleSchemaFactory;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\SchemaFactory;
use Popo\Writer\WriterFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class PopoFactory
{
    protected ?OutputInterface $output;

    public function createFinderFactory(): FinderFactory
    {
        return new FinderFactory();
    }

    public function createLoaderFactory(): LoaderFactory
    {
        return new LoaderFactory();
    }

    public function createReaderFactory(): ReaderFactory
    {
        return new ReaderFactory();
    }

    public function createSchemaFactory(): SchemaFactory
    {
        return new SchemaFactory(
            $this->createFinderFactory(),
            $this->createLoaderFactory(),
            $this->createReaderFactory(),
            $this->createBundleSchemaFactory()
        );
    }

    public function createBundleSchemaFactory(): BundleSchemaFactory
    {
        return new BundleSchemaFactory();
    }

    public function createWriterFactory(): WriterFactory
    {
        return new WriterFactory();
    }

    public function createBuilderFactory(): BuilderFactory
    {
        return new BuilderFactory(
            $this->createLoaderFactory(),
            $this->createGeneratorFactory(),
            $this->createSchemaFactory(),
            $this->createWriterFactory()
        );
    }

    public function createGeneratorFactory(): GeneratorFactory
    {
        return new GeneratorFactory(
            $this->createReaderFactory()
        );
    }

    public function createPopoModel(Configurator $configurator): Popo
    {
        $provider = $this->createConfiguratorProvider();

        $configuratorOrig = clone $configurator;

        $configurator = $provider->configurePopo($configurator);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configurator);
        $generatorPopo = $this->createBuilderFactory()->createPopoGeneratorBuilder()->build($configurator, $pluginContainer);

        $configurator = clone $configuratorOrig;
        $configurator = $provider->configureAbstract($configurator);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configurator);
        $generatorAbstract = $this->createBuilderFactory()->createPopoGeneratorBuilder()->build($configurator, $pluginContainer);

        $configurator = clone $configuratorOrig;
        $configurator = $provider->configureDto($configurator);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configurator);
        $generatorDto = $this->createBuilderFactory()->createPopoGeneratorBuilder()->build($configurator, $pluginContainer);

        return new Popo(
            $this->createSchemaFactory()->createSchemaBuilder(),
            $this->createSchemaFactory()->createSchemaMerger(),
            $this->createWriterFactory()->createFileWriter($generatorPopo),
            $this->createWriterFactory()->createFileWriter($generatorDto),
            $this->createWriterFactory()->createFileWriter($generatorAbstract),
            $this->createProgressIndicator($configuratorOrig)
        );
    }

    protected function createConfiguratorProvider(): ConfiguratorProvider
    {
        return new ConfiguratorProvider();
    }

    protected function createProgressIndicator(Configurator $configurator, int $max = 0): ProgressIndicator
    {
        return new ProgressIndicator($this->getOutput(), $configurator, $max);
    }

    protected function getOutput(): OutputInterface
    {
        if (empty($this->output)) {
            $this->output = new ConsoleOutput();
        }
        return $this->output;
    }

    public function setOutput(?OutputInterface $output): void
    {
        $this->output = $output;
    }
}
