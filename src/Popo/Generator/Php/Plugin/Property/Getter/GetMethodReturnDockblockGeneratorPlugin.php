<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function sprintf;
use function trim;

class GetMethodReturnDockblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_RETURN_DOCKBLOCK>>';

    public function generate(Schema $schema, Property $property): string
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
