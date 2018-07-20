<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Schema\Reader\PropertyExplorerInterface;
use Popo\Schema\Validator\SchemaValidatorInterface;

interface SchemaFactoryInterface
{
    public function createSchemaBuilder(): SchemaBuilderInterface;

    public function createSchemaMerger(): SchemaMergerInterface;

    public function createSchemaValidator(): SchemaValidatorInterface;

    public function createPropertyExplorer(): PropertyExplorerInterface;
}
