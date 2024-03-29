<?php declare(strict_types = 1);

namespace Popo\Schema\Validator\Definition;

use Popo\PopoDefinesInterface;
use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class ConfigDefinition implements ConfigurableInterface
{
    public const ALIAS = PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG;

    public function configure(DefinitionConfigurator $definition):void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('namespace')->isRequired()->end()
                ->scalarNode('outputPath')->isRequired()->end()
                ->scalarNode('namespaceRoot')->end()
                ->scalarNode('extend')->end()
                ->scalarNode('implement')->end()
                ->scalarNode('comment')->end()
                ->scalarNode('phpComment')->end()
                ->scalarNode('attribute')->end()
                ->arrayNode('attributes')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->variableNode('value')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('use')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('trait')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('phpFilePluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('namespacePluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('classPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('propertyPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('mappingPolicyPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ->end();
    }
}