<?php declare(strict_types = 1);

namespace Popo\Schema\Validator\Definition;

use Popo\PopoDefinesInterface;
use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class DefaultDefinition implements ConfigurableInterface
{
    public const ALIAS = PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT;

    public function configure(DefinitionConfigurator $definition):void
    {
        $definition->rootNode()
                ->ignoreExtraKeys(false)
                ->children()
                    ->variableNode('default')->end()
                ->end()
            ->end();
    }
}