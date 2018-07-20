<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

interface SchemaInterface
{
    public function getName(): string;

    public function setName(string $name): SchemaInterface;

    public function getSchema(): array;

    public function setSchema(array $schema): SchemaInterface;

    public function getClassName(): string;

    public function getNamespaceName(): string;

    public function toArray(): array;
}
