<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\FileWriter;
use Popo\Builder\PopoBuilder;
use Popo\Builder\PopoGenerator;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\Model\Generate\GenerateModel;
use Popo\Model\Report\ReportModel;
use Popo\Plugin\ExternalPluginContainer;
use Popo\Plugin\PluginContainer;
use Popo\Plugin\PluginContainerInterface;
use Popo\Schema\Config\ConfigMerger;
use Popo\Schema\Generator\SchemaGenerator;
use Popo\Schema\Generator\SchemaGeneratorInterface;
use Popo\Schema\Inspector\SchemaInspector;
use Popo\Schema\Inspector\SchemaInspectorInterface;
use Popo\Schema\Mapper\SchemaMapper;
use Popo\Schema\Validator\Definition\ConfigDefinition;
use Popo\Schema\Validator\Definition\DefaultDefinition;
use Popo\Schema\Validator\Definition\PropertyDefinition;
use Popo\Schema\Validator\Validator;
use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Finder\Finder;

class PopoFactory
{
    public function createPopoModel(PopoConfigurator $configurator): GenerateModel
    {
        return new GenerateModel(
            $this->createSchemaBuilder(),
            $this->createPopoGenerator($configurator),
        );
    }

    public function createReportModel(): ReportModel
    {
        return new ReportModel(
            $this->createSchemaLoader()
        );
    }

    public function createExternalPluginContainer(): ExternalPluginContainer
    {
        return new ExternalPluginContainer();
    }

    protected function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->createSchemaLoader(),
            $this->createConfigMerger(),
            $this->createValidator(),
        );
    }

    protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader(
            $this->createFileLocator(),
            $this->createLoader()
        );
    }

    protected function createSchemaMapper(PopoConfigurator $configurator): SchemaMapper
    {
        return new SchemaMapper(
            $this->createPluginContainer($configurator)->createMappingPolicyPlugins()
        );
    }

    protected function createPopoGenerator(PopoConfigurator $configurator): PopoGenerator
    {
        return new PopoGenerator(
            $this->createPopoBuilder($configurator),
            $this->createFileWriter()
        );
    }

    protected function createPopoBuilder(PopoConfigurator $configurator): PopoBuilder
    {
        return new PopoBuilder(
            $this->createSchemaInspector(),
            $this->createSchemaGenerator(),
            $this->createSchemaMapper($configurator),
            $this->createPluginContainer($configurator),
        );
    }

    protected function createSchemaInspector(): SchemaInspectorInterface
    {
        return new SchemaInspector();
    }

    protected function createSchemaGenerator(): SchemaGeneratorInterface
    {
        return new SchemaGenerator(
            $this->createSchemaInspector()
        );
    }

    protected function createFileLocator(): FileLocator
    {
        return new FileLocator(Finder::create());
    }

    protected function createLoader(): YamlLoader
    {
        return new YamlLoader();
    }

    protected function createConfigMerger(): ConfigMerger
    {
        return new ConfigMerger();
    }

    protected function createFileWriter(): FileWriter
    {
        return new FileWriter();
    }

    protected function createPluginContainer(PopoConfigurator $configurator): PluginContainerInterface
    {
        return new PluginContainer($configurator);
    }

    public function createValidator(): Validator
    {
        return new Validator($this->creatValidatorPlugins());
    }

    /**
     * @return array<ConfigurableInterface>
     */
    protected function creatValidatorPlugins(): array
    {
        return [
            DefaultDefinition::ALIAS => new DefaultDefinition(),
            ConfigDefinition::ALIAS => new ConfigDefinition(),
            PropertyDefinition::ALIAS => new PropertyDefinition(
                $this->createSchemaInspector()
            ),
        ];
    }
}
