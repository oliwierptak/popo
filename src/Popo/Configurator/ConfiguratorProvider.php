<?php declare(strict_types = 1);

namespace Popo\Configurator;

use Popo\Configurator;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnDockblockGeneratorPlugin as DtoSetMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnDockblockGeneratorPlugin as PopoSetMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnTypeGeneratorPlugin as PopoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ImplementsInterfaceGeneratorPlugin as PopoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ReturnTypeGeneratorPlugin as PopoReturnTypeGeneratorPlugin;

class ConfiguratorProvider
{
    /**
     * @param Configurator $configurator
     *
     * @return Configurator
     * @throws \InvalidArgumentException
     */
    public function configurePopo(Configurator $configurator): Configurator
    {
        $this->assertConfiguration($configurator);

        return $this->configurePopoPlugins($configurator);
    }

    /**
     * @param \Popo\Configurator $configurator
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function assertConfiguration(Configurator $configurator): void
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
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Required %s directory does not exist under path: %s',
                        $type,
                        $path
                    )
                );
            }
        }
    }

    protected function configurePopoPlugins(Configurator $configurator): Configurator
    {
        $configurator
            ->setSchemaPluginClasses(
                [
                    PopoImplementsInterfaceGeneratorPlugin::PATTERN => PopoImplementsInterfaceGeneratorPlugin::class,
                    PopoReturnTypeGeneratorPlugin::PATTERN => PopoReturnTypeGeneratorPlugin::class,
                ]
            )
            ->setPropertyPluginClasses(
                [
                    PopoSetMethodReturnDockblockGeneratorPlugin::PATTERN => PopoSetMethodReturnDockblockGeneratorPlugin::class,
                    PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
                ]
            )
            ->setCollectionPluginClasses(
                [
                    PopoSetMethodReturnDockblockGeneratorPlugin::PATTERN => PopoSetMethodReturnDockblockGeneratorPlugin::class,
                    PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
                ]
            );

        return $configurator;
    }

    /**
     * @param Configurator $configurator
     *
     * @return Configurator
     * @throws \InvalidArgumentException
     */
    public function configureDto(Configurator $configurator): Configurator
    {
        $configurator = $this->configureDtoPlugins($configurator);
        $configurator
            ->setExtension('Interface.php')
            ->getSchemaConfigurator()
            ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
            ->setPropertyTemplateFilename('interface/php.interface.property.tpl')
            ->setCollectionTemplateFilename('interface/php.interface.collection.tpl');

        $this->assertConfiguration($configurator);

        return $configurator;
    }

    protected function configureDtoPlugins(Configurator $configurator): Configurator
    {
        $configurator
            ->setSchemaPluginClasses(
                [
                    DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                    DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
                ]
            )
            ->setPropertyPluginClasses(
                [
                    DtoSetMethodReturnDockblockGeneratorPlugin::PATTERN => DtoSetMethodReturnDockblockGeneratorPlugin::class,
                    DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
                ]
            )
            ->setCollectionPluginClasses(
                [
                    DtoSetMethodReturnDockblockGeneratorPlugin::PATTERN => DtoSetMethodReturnDockblockGeneratorPlugin::class,
                    DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
                ]
            );

        return $configurator;
    }

    /**
     * @param Configurator $configurator
     *
     * @return Configurator
     * @throws \InvalidArgumentException
     */
    public function configureAbstract(Configurator $configurator): Configurator
    {
        $configurator = $this->configurePopoPlugins($configurator);
        $configurator
            ->getSchemaConfigurator()
            ->setSchemaTemplateFilename('php.schema-abstract.tpl');

        $this->assertConfiguration($configurator);

        return $configurator;
    }
}
