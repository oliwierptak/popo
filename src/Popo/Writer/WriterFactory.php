<?php

declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Popo\Writer\Bundle\BundleProjectWriter;
use Popo\Writer\File\FileWriter;
use Popo\Writer\File\FileWriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WriterFactory implements WriterFactoryInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    public function createFileWriter(GeneratorInterface $generator): FileWriterInterface
    {
        return new FileWriter($generator);
    }

    public function createBundleProjectWriter(GeneratorInterface $generator, string $namespace): WriterInterface
    {
        $fileWriter = $this->createFileWriter($generator);

        return new BundleProjectWriter($fileWriter, $namespace, $this->output);
    }

    public function setConsoleOutput(?OutputInterface $output): void
    {
        $this->output = $output;
    }
}
