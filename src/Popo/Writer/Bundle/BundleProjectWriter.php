<?php

declare(strict_types = 1);

namespace Popo\Writer\Bundle;

use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Writer\File\FileWriterInterface;
use Popo\Writer\WriterInterface;
use SplFileInfo;

class BundleProjectWriter implements WriterInterface
{
    /**
     * @var \Popo\Writer\File\FileWriterInterface
     */
    protected $fileWriter;

    /**
     * @var string
     */
    protected $namespace;

    public function __construct(FileWriterInterface $fileWriter, string $namespace)
    {
        $this->fileWriter = $fileWriter;
        $this->namespace = $namespace;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface[] $schemaFiles
     * @param string $extension
     * @param string $outputDirectory
     *
     * @return void
     */
    public function write(array $schemaFiles, string $extension, string $outputDirectory): void
    {
        foreach ($schemaFiles as $schemaFilename => $bundleSchema) {
            $filename = $this->generateFilename($bundleSchema, $extension, $outputDirectory);
            $this->writePopo($bundleSchema, $filename);
        }
    }

    protected function generateFilename(
        BundleSchemaInterface $bundleSchema,
        string $extension,
        string $outputDirectory
    ): SplFileInfo {
        $filename = $this->getOutputFilename($bundleSchema, $extension);
        $dir = $this->getOutputDirectory($bundleSchema, $outputDirectory);

        $file = new SplFileInfo($dir . $filename);

        return $file;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchema
     * @param string $outputDirectory
     *
     * @return string
     */
    protected function getOutputDirectory(BundleSchemaInterface $bundleSchema, string $outputDirectory): string
    {
        $outputDirectory = \trim($outputDirectory);
        $outputDirectory = \str_replace('\\', \DIRECTORY_SEPARATOR, $outputDirectory);

        return $outputDirectory;
    }

    protected function getOutputFilename(BundleSchemaInterface $bundleSchema, string $extension): string
    {
        $nameTokens = \explode('\\', $bundleSchema->getSchema()->getName());
        $name = \array_pop($nameTokens);
        $name .= $extension;

        return $name;
    }

    protected function writePopo(BundleSchemaInterface $bundleSchema, SplFileInfo $filename): void
    {
        $name = $this->generateProjectSchemaName($bundleSchema);

        $bundleSchema
            ->getSchema()
            ->setName($name);

        $this->fileWriter->write($filename->getPathname(), $bundleSchema->getSchema());
    }

    protected function generateProjectSchemaName(BundleSchemaInterface $bundleSchema): string
    {
        $nameTokens = \explode('\\', $bundleSchema->getSchema()->getName());
        $name = \array_pop($nameTokens);

        return $this->namespace . '\\' . $name;
    }
}
