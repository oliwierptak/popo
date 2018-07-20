<?php

declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Popo\Writer\File\FileWriterInterface;

interface WriterFactoryInterface
{
    public function createFileWriter(GeneratorInterface $generator): FileWriterInterface;

    public function createBundleProjectWriter(GeneratorInterface $generator, string $namespace): WriterInterface;
}
