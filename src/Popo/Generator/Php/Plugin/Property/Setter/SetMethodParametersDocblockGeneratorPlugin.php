<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;
use function sprintf;
use function trim;

class SetMethodParametersDocblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_PARAM_DOCKBLOCK>>';

    public function generate(PropertyInterface $property): string
    {
        $docblock = trim($property->getDocblock());

        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $string = sprintf(
            '<<DOCBLOCK_TYPE>>|null $<<PROPERTY_NAME>>%s',
            $docblock
        );

        return $string;
    }
}
