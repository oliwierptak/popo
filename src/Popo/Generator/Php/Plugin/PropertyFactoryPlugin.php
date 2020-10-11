<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin;

use Popo\Generator\Php\Plugin\Property\DocblockTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Getter\GetMethodNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Getter\GetMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Getter\GetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\PropertyNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Requester\HasMethodNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Requester\RequireMethodNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Requester\RequireMethodReturnDockblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Requester\RequireMethodReturnTypeCastPlugin;
use Popo\Generator\Php\Plugin\Property\Requester\RequireMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\SetMethodNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\SetMethodParametersDocblockGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\SetMethodParametersGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\SetMethodReturnDockblockGeneratorPlugin;
use Popo\Plugin\Factory\PropertyFactoryPluginInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

class PropertyFactoryPlugin implements PropertyFactoryPluginInterface
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
     * @return \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    public function createPluginCollection(): array
    {
        return [
            GetMethodReturnTypeGeneratorPlugin::PATTERN => new GetMethodReturnTypeGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            GetMethodNameGeneratorPlugin::PATTERN => new GetMethodNameGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            GetMethodReturnDockblockGeneratorPlugin::PATTERN => new GetMethodReturnDockblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodNameGeneratorPlugin::PATTERN => new SetMethodNameGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodParametersGeneratorPlugin::PATTERN => new SetMethodParametersGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodReturnDockblockGeneratorPlugin::PATTERN => new SetMethodReturnDockblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            SetMethodParametersDocblockGeneratorPlugin::PATTERN => new SetMethodParametersDocblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            RequireMethodReturnDockblockGeneratorPlugin::PATTERN => new RequireMethodReturnDockblockGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            RequireMethodNameGeneratorPlugin::PATTERN => new RequireMethodNameGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            RequireMethodReturnTypeGeneratorPlugin::PATTERN => new RequireMethodReturnTypeGeneratorPlugin(
                $this->getPropertyExplorer()
            ),
            RequireMethodReturnTypeCastPlugin::PATTERN => new RequireMethodReturnTypeCastPlugin(
                $this->getPropertyExplorer()
            ),
            HasMethodNameGeneratorPlugin::PATTERN => new HasMethodNameGeneratorPlugin(
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

    protected function getPropertyExplorer(): PropertyExplorerInterface
    {
        return $this->propertyExplorer;
    }
}
