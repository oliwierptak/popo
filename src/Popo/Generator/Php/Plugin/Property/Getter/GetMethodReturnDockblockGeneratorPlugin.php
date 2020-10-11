<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;
use function sprintf;
use function trim;

class GetMethodReturnDockblockGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_RETURN_DOCKBLOCK>>';

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
            $docblockType . '|null%s',
            $docblock
        );

        return $string;
    }
}
