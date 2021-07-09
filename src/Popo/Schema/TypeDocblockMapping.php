<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\PopoDefinesInterface;

class TypeDocblockMapping
{
    protected array $mapping = [
        PopoDefinesInterface::PROPERTY_TYPE_ARRAY => PopoDefinesInterface::DOCBLOCK_TYPE_ARRAY,
        PopoDefinesInterface::PROPERTY_TYPE_BOOL => PopoDefinesInterface::DOCBLOCK_TYPE_BOOL,
        PopoDefinesInterface::PROPERTY_TYPE_FLOAT => PopoDefinesInterface::DOCBLOCK_TYPE_FLOAT,
        PopoDefinesInterface::PROPERTY_TYPE_INT => PopoDefinesInterface::DOCBLOCK_TYPE_INT,
        PopoDefinesInterface::PROPERTY_TYPE_STRING => PopoDefinesInterface::DOCBLOCK_TYPE_STRING,
        PopoDefinesInterface::PROPERTY_TYPE_MIXED => PopoDefinesInterface::DOCBLOCK_TYPE_MIXED,
        PopoDefinesInterface::PROPERTY_TYPE_CONST => PopoDefinesInterface::DOCBLOCK_TYPE_CONST,
        PopoDefinesInterface::PROPERTY_TYPE_POPO => PopoDefinesInterface::DOCBLOCK_TYPE_POPO,
    ];

    public function translate(string $propertyType): string
    {
        return $this->mapping[$propertyType];
    }
}
