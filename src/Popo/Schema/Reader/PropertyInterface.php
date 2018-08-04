<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

interface PropertyInterface
{
    public function getName(): string;

    /**
     * @return mixed|null
     */
    public function getDefault();

    public function getDocblock(): string;

    public function getType(): string;

    public function getCollectionItem(): string;

    public function getSingular(): string;

    public function isCollectionItem(): bool;

    public function hasDefault(): bool;

    public function getSchema(): SchemaInterface;

    public function toArray(): array;
}
