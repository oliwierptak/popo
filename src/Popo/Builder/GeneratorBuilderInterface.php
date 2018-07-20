<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorInterface;

interface GeneratorBuilderInterface
{
    /**
     * Specification:
     * - Loads content of <<php.schema.tpl>> using template directory of configurator
     * - Loads content of <<php.property.tpl>> using template directory of configurator
     * - Creates default property plugin collection and merges it with plugin container
     * - Creates default schema plugin collection and merges it with plugin container
     * - Creates Schema generator
     * - Returns created SchemaGenerator instance
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     *
     * @return \Popo\Generator\GeneratorInterface
     */
    public function build(BuilderConfiguratorInterface $configurator, PluginContainerInterface $pluginContainer): GeneratorInterface;
}
