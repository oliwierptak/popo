<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorInterface;
use Popo\Writer\WriterFactoryInterface;

class BuilderWriter implements BuilderWriterInterface
{
    /**
     * @var \Popo\Writer\WriterFactoryInterface
     */
    protected $writerFactory;

    public function __construct(WriterFactoryInterface $writerFactory)
    {
        $this->writerFactory = $writerFactory;
    }

    public function write(BuilderConfigurator $configurator, GeneratorInterface $generator, array $schemaFiles): int
    {
        $this->writerFactory->setConsoleOutput($configurator->getOutput());

        $bundleWriter = $this->writerFactory->createBundleProjectWriter(
            $generator,
            $configurator->getNamespace()
        );

        $numberOfFilesGenerated = $bundleWriter->write(
            $schemaFiles, $configurator->getExtension(), $configurator->getOutputDirectory(), false
        );

        if ($configurator->getWithInterface()) {
            $numberOfFilesGenerated += $bundleWriter->write(
                $schemaFiles, $configurator->getExtension(), $configurator->getOutputDirectory(), true
            );
        }

        return $numberOfFilesGenerated;
    }
}
