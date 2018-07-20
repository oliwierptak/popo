<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfiguratorInterface;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnTypeGeneratorPlugin as PopoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ImplementsInterfaceGeneratorPlugin as PopoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ReturnTypeGeneratorPlugin as PopoReturnTypeGeneratorPlugin;
use Popo\Schema\Reader\SchemaInterface;

class StringDirector extends AbstractPopoDirector implements StringDirectorInterface
{
    /**
     * @var \Popo\Schema\Reader\SchemaInterface
     */
    protected $schema;

    /**
     * @var string
     */
    protected $generatedString;

    public function generateDtoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        $this->generatedString = '';
        $this->schema = clone $schema;

        $this->schema->setName(
            $configurator->getNamespace() . '\\' . \ltrim($this->schema->getName(), '\\')
        );

        $configurator = $this->configureDtoPlugins($configurator);
        $this->generate($configurator);

        return $this->generatedString;
    }

    public function generateDtoInterfaceString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        $this->generatedString = '';
        $this->schema = clone $schema;

        $this->schema->setName(
            $configurator->getNamespace() . '\\' . \ltrim($this->schema->getName(), '\\')
        );

        $configurator
            ->getSchemaConfigurator()
                ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
                ->setPropertyTemplateFilename('interface/php.interface.property.tpl');

        $configurator = $this->configureDtoPlugins($configurator);
        $this->generate($configurator);

        return $this->generatedString;
    }

    public function generatePopoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        $this->generatedString = '';
        $this->schema = clone $schema;

        $this->schema->setName(
            $configurator->getNamespace() . '\\' . \ltrim($this->schema->getName(), '\\')
        );

        $configurator = $this->configurePopoPlugins($configurator);
        $this->generate($configurator);

        return $this->generatedString;
    }

    protected function generate(BuilderConfiguratorInterface $configurator): void
    {
        $generatorBuilder = $this->builderFactory
            ->createBuilder();

        $pluginContainer = $this->builderFactory
            ->createPluginContainer($configurator);

        $generator = $generatorBuilder->build($configurator, $pluginContainer);

        $this->generatedString = $generator->generate($this->schema);
    }

    protected function configureDtoPlugins(BuilderConfiguratorInterface $configurator): BuilderConfiguratorInterface
    {
        $configurator
            ->setSchemaPluginClasses([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->setPropertyPluginClasses([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        return $configurator;
    }

    protected function configurePopoPlugins(BuilderConfiguratorInterface $configurator): BuilderConfiguratorInterface
    {
        $configurator
            ->setSchemaPluginClasses([
                PopoImplementsInterfaceGeneratorPlugin::PATTERN => PopoImplementsInterfaceGeneratorPlugin::class,
                PopoReturnTypeGeneratorPlugin::PATTERN => PopoReturnTypeGeneratorPlugin::class,
            ])
            ->setPropertyPluginClasses([
                PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        return $configurator;
    }
}
