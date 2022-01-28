<?php

declare(strict_types = 1);

namespace Popo\Schema\Generator;

use Popo\Schema\Property\Property;
use Popo\Schema\Schema;

interface SchemaGeneratorInterface
{
    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string;

    public function generatePopoItemType(Schema $schema, Property $property): string;
}
