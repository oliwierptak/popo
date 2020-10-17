<?php

declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Popo\Writer\File\FileWriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface WriterFactoryInterface
{
    public function createFileWriter(GeneratorInterface $generator): FileWriterInterface;

    public function createBundleProjectWriter(GeneratorInterface $generator, string $namespace): WriterInterface;

    public function setConsoleOutput(?OutputInterface $output): void;
}
