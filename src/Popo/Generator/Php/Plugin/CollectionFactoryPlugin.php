<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin;

use Popo\Generator\Php\Plugin\Property\Collection\AddItemMethodNamePlugin;
use Popo\Generator\Php\Plugin\Property\Collection\AddItemMethodParametersDocblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Collection\AddItemMethodParametersGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\DocblockTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\PropertyNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\SetMethodParametersDocblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnDockblockGeneratorPlugin;

class CollectionFactoryPlugin extends PropertyFactoryPlugin
{
    /**
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    public function createPluginCollection(): array
    {
        return [
            AddItemMethodNamePlugin::PATTERN => new AddItemMethodNamePlugin(
                $this->getPropertyExplorer()
            ),
            AddItemMethodParametersGeneratorPlugin::PATTERN => new AddItemMethodParametersGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            AddItemMethodParametersDocblockGeneratorPlugin::PATTERN => new AddItemMethodParametersDocblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodReturnDockblockGeneratorPlugin::PATTERN => new SetMethodReturnDockblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodParametersDocblockGeneratorPlugin::PATTERN => new SetMethodParametersDocblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            //run last
            DocblockTypeGeneratorPlugin::PATTERN => new DocblockTypeGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            PropertyNameGeneratorPlugin::PATTERN => new PropertyNameGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
        ];
    }
}
