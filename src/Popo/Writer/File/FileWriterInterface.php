<?php

declare(strict_types = 1);

namespace Popo\Writer\File;

use Popo\Schema\Reader\SchemaInterface;

interface FileWriterInterface
{
    public function write(string $filename, SchemaInterface $schema): void;
}
