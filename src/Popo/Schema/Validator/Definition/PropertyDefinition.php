<?php declare(strict_types = 1);

namespace Popo\Schema\Validator\Definition;

use Popo\PopoDefinesInterface;
use Popo\Schema\Inspector\SchemaInspectorInterface;
use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class PropertyDefinition implements ConfigurableInterface
{
    public const ALIAS = PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY;

    public function __construct(private SchemaInspectorInterface $schemaInspector) {}

    public function configure(DefinitionConfigurator $definition):void
    {
        $definition->rootNode()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('type')->end()
                    ->scalarNode('comment')->end()
                    ->variableNode('default')->end()
                    ->scalarNode('itemType')->end()
                    ->scalarNode('itemName')->end()
                    ->scalarNode('phpComment')->end()
                    ->scalarNode('attribute')->end()
                    ->scalarNode('mappingPolicyValue')->end()
                    ->arrayNode('extra')
                        ->children()
                            ->scalarNode('timezone')->end()
                            ->scalarNode('format')->end()
                        ->end()
                    ->end()
                    ->arrayNode('attributes')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('name')->isRequired()->end()
                                ->variableNode('value')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('mappingPolicy')
                        ->beforeNormalization()
                            ->ifArray()
                            ->then(function (array $mappingPolicy) {
                                $result = [];
                                foreach ($mappingPolicy as $mapping) {
                                    $result[] = $this->schemaInspector->isLiteral($mapping)
                                        ? constant($mapping)
                                        : $mapping;
                                }

                                return $result;
                            })
                        ->end()
                        ->enumPrototype()
                            ->values(['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel'])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}