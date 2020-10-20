<?php

declare(strict_types = 1);

namespace Popo\Plugin\Factory;

interface PropertyFactoryPluginInterface
{
    /**
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    public function createPluginCollection(): array;
}
