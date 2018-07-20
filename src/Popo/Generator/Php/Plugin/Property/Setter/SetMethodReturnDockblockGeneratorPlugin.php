<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class SetMethodReturnDockblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_DOCKBLOCK>>';

    public function generate(PropertyInterface $property): string
    {
        $docblock = \trim($property->getDocblock());
        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $generated = \sprintf(
            '%s%s',
            'self',
            $docblock
        );

        return $generated;
    }
}
