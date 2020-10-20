<?php

declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WriterFactory
{
    protected ?OutputInterface $output;

    public function createFileWriter(GeneratorInterface $generator): FileWriter
    {
        return new FileWriter($generator);
    }

    public function createProjectWriter(GeneratorInterface $generator, string $namespace): ProjectWriter
    {
        $fileWriter = $this->createFileWriter($generator);

        return new ProjectWriter($fileWriter, $namespace, $this->output);
    }

    public function setConsoleOutput(?OutputInterface $output): void
    {
        $this->output = $output;
    }
}
