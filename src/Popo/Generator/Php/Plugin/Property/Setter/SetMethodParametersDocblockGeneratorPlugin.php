<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;
use function sprintf;
use function trim;

class SetMethodParametersDocblockGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_PARAM_DOCKBLOCK>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $docblock = trim($property->getDocblock());
        $docblockType = '<<DOCBLOCK_TYPE>>';

        if ($property->isCollectionItem()) {
            $docblockType = sprintf('%s[]', $property->getCollectionItem());
        }

        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $string = sprintf(
            '%s|null $<<PROPERTY_NAME>>%s',
            $docblockType,
            $docblock
        );

        return $string;
    }
}
