<?php

declare(strict_types = 1);

namespace Popo\Builder;

interface BuilderFactoryInterface
{
    public function createBuilder(): GeneratorBuilderInterface;

    public function createBuilderWriter(): BuilderWriterInterface;

    public function createPluginContainer(BuilderConfigurator $configurator): PluginContainerInterface;
}
