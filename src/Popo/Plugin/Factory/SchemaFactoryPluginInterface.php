<?php declare(strict_types = 1);

namespace Popo\Plugin\Factory;

interface SchemaFactoryPluginInterface
{
    /**
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    public function createPluginCollection(): array;
}
