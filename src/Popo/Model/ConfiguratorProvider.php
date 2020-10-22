<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Configurator;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnTypeGeneratorPlugin as PopoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnDockblockGeneratorPlugin as DtoSetMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnDockblockGeneratorPlugin as PopoSetMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\FromArrayResultPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ImplementsInterfaceGeneratorPlugin as PopoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ReturnTypeGeneratorPlugin as PopoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\ToArrayResultPlugin;

class ConfiguratorProvider
{
    public function configurePopo(Configurator $configurator): Configurator
    {
        return $this->configurePopoPlugins($configurator);
    }

    public function configureDto(Configurator $configurator): Configurator
    {
        $configurator = $this->configureDtoPlugins($configurator);
        $configurator
            ->setExtension('Interface.php')
            ->getSchemaConfigurator()
            ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
            ->setPropertyTemplateFilename('interface/php.interface.property.tpl')
            ->setCollectionTemplateFilename('interface/php.interface.collection.tpl');

        return $configurator;
    }

    public function configureAbstract(Configurator $configurator): Configurator
    {
        $configurator = $this->configurePopoPlugins($configurator);
        $configurator
            ->getSchemaConfigurator()
            ->setSchemaTemplateFilename('php.schema-abstract.tpl');

        return $configurator;
    }

    protected function configureDtoPlugins(Configurator $configurator): Configurator
    {
        $configurator
            ->setSchemaPluginClasses([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->setArrayablePluginClasses([
                FromArrayResultPlugin::PATTERN => FromArrayResultPlugin::class,
                ToArrayResultPlugin::PATTERN => ToArrayResultPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->setPropertyPluginClasses([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
                DtoSetMethodReturnDockblockGeneratorPlugin::PATTERN => DtoSetMethodReturnDockblockGeneratorPlugin::class,
            ])
            ->setCollectionPluginClasses([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
                DtoSetMethodReturnDockblockGeneratorPlugin::PATTERN => DtoSetMethodReturnDockblockGeneratorPlugin::class,
            ]);

        return $configurator;
    }

    protected function configurePopoPlugins(Configurator $configurator): Configurator
    {
        $configurator
            ->setSchemaPluginClasses([
                PopoImplementsInterfaceGeneratorPlugin::PATTERN => PopoImplementsInterfaceGeneratorPlugin::class,
                PopoReturnTypeGeneratorPlugin::PATTERN => PopoReturnTypeGeneratorPlugin::class
            ])
            ->setArrayablePluginClasses([
                FromArrayResultPlugin::PATTERN => FromArrayResultPlugin::class,
                ToArrayResultPlugin::PATTERN => ToArrayResultPlugin::class,
                PopoReturnTypeGeneratorPlugin::PATTERN => PopoReturnTypeGeneratorPlugin::class
            ])
            ->setPropertyPluginClasses([
                PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
                PopoSetMethodReturnDockblockGeneratorPlugin::PATTERN => PopoSetMethodReturnDockblockGeneratorPlugin::class,
            ])
            ->setCollectionPluginClasses([
                PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
                PopoSetMethodReturnDockblockGeneratorPlugin::PATTERN => PopoSetMethodReturnDockblockGeneratorPlugin::class,
            ]);

        return $configurator;
    }
}
