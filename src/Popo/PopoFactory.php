<?php declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderFactory;
use Popo\Configurator\ConfiguratorProvider;
use Popo\Finder\FinderFactory;
use Popo\Generator\GeneratorFactory;
use Popo\Generator\GeneratorInterface;
use Popo\Generator\SchemaGenerator;
use Popo\Model\Helper\ProgressIndicator;
use Popo\Model\Popo;
use Popo\Schema\Bundle\BundleSchemaFactory;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\SchemaFactory;
use Popo\Writer\FileWriter;
use Popo\Writer\SchemaWriter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class PopoFactory
{
    protected ?OutputInterface $output;

    public function createPopoModel(Configurator $configurator): Popo
    {
        return new Popo(
            $this->createSchemaFactory()->createSchemaBuilder(),
            $this->createSchemaFactory()->createSchemaMerger(),
            $this->createSchemaWriter($configurator),
            $this->createProgressIndicator($configurator)
        );
    }

    protected function createPopoSchemaGenerator(Configurator $configurator): SchemaGenerator
    {
        $configuratorPopo = clone $configurator;
        $configuratorPopo = $this->createConfiguratorProvider()->configurePopo($configuratorPopo);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configuratorPopo);

        return $this->createBuilderFactory()->createPopoGeneratorBuilder()->build(
            $configuratorPopo,
            $pluginContainer
        );
    }

    protected function createConfiguratorProvider(): ConfiguratorProvider
    {
        return new ConfiguratorProvider();
    }

    public function createBuilderFactory(): BuilderFactory
    {
        return new BuilderFactory(
            $this->createLoaderFactory(),
            $this->createGeneratorFactory(),
            $this->createSchemaFactory(),
        );
    }

    public function createLoaderFactory(): LoaderFactory
    {
        return new LoaderFactory();
    }

    public function createGeneratorFactory(): GeneratorFactory
    {
        return new GeneratorFactory(
            $this->createReaderFactory()
        );
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

    public function createFinderFactory(): FinderFactory
    {
        return new FinderFactory();
    }

    public function createBundleSchemaFactory(): BundleSchemaFactory
    {
        return new BundleSchemaFactory();
    }

    protected function createDtoSchemaGenerator(Configurator $configurator): SchemaGenerator
    {
        $configuratorPopo = clone $configurator;
        $configuratorPopo = $this->createConfiguratorProvider()->configureDto($configuratorPopo);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configuratorPopo);

        return $this->createBuilderFactory()->createPopoGeneratorBuilder()->build(
            $configuratorPopo,
            $pluginContainer
        );
    }

    protected function createAbstractSchemaGenerator(Configurator $configurator): SchemaGenerator
    {
        $configuratorPopo = clone $configurator;
        $configuratorPopo = $this->createConfiguratorProvider()->configureAbstract($configuratorPopo);
        $pluginContainer = $this->createBuilderFactory()->createPluginContainer($configuratorPopo);

        return $this->createBuilderFactory()->createPopoGeneratorBuilder()->build(
            $configuratorPopo,
            $pluginContainer
        );
    }

    protected function createFileWriter(GeneratorInterface $generator): FileWriter
    {
        return new FileWriter($generator);
    }

    protected function createProgressIndicator(Configurator $configurator): ProgressIndicator
    {
        return new ProgressIndicator($this->getOutput(), $configurator);
    }

    protected function createSchemaWriter(Configurator $configurator): SchemaWriter
    {
        return new SchemaWriter(
            $this->createFileWriter($this->createPopoSchemaGenerator($configurator)),
            $this->createFileWriter($this->createDtoSchemaGenerator($configurator)),
            $this->createFileWriter($this->createAbstractSchemaGenerator($configurator))
        );
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
