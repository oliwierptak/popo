<?php

declare(strict_types = 1);

namespace Popo\Plugin\Factory;

interface PropertyFactoryPluginInterface
{
    /**
     * @return \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    public function createPluginCollection(): array;
}
