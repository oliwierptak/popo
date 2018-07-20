<?php

declare(strict_types = 1);

namespace Popo\Schema;

interface SchemaConfiguratorInterface
{
    public function getSchemaPath(): string;

    public function setSchemaPath(string $schemaPath): SchemaConfiguratorInterface;

    public function getSchemaFilename(): string;

    public function setSchemaFilename(string $schemaFilename): SchemaConfiguratorInterface;

    public function resolveBundleName(string $schemaFilename, string $delimiter = '.'): string;

    public function getSchemaTemplateFilename(): string;

    public function setSchemaTemplateFilename(string $schemaTemplateFilename): SchemaConfiguratorInterface;

    public function getPropertyTemplateFilename(): string;

    public function setPropertyTemplateFilename(string $propertyTemplateFilename): SchemaConfiguratorInterface;
}
