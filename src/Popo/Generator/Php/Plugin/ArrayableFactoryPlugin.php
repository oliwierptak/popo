<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin;

use Popo\Generator\Php\Plugin\Schema\FromArrayResultPlugin;
use Popo\Generator\Php\Plugin\Schema\ToArrayResultPlugin;
use Popo\Plugin\Factory\SchemaFactoryPluginInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

class ArrayableFactoryPlugin implements SchemaFactoryPluginInterface
{
    /**
     * @var \Popo\Schema\Reader\PropertyExplorerInterface
     */
    protected $propertyExplorer;

    public function __construct(PropertyExplorerInterface $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    /**
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    public function createPluginCollection(): array
    {
        return [
            ToArrayResultPlugin::PATTERN => new ToArrayResultPlugin(
                $this->getPropertyExplorer()
            ),
            FromArrayResultPlugin::PATTERN => new FromArrayResultPlugin(
                $this->getPropertyExplorer()
            ),
        ];
    }

    protected function getPropertyExplorer(): PropertyExplorerInterface
    {
        return $this->propertyExplorer;
    }
}