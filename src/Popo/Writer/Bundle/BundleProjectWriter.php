<?php

declare(strict_types = 1);

namespace Popo\Writer\Bundle;

use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Writer\File\FileWriterInterface;
use Popo\Writer\WriterInterface;
use SplFileInfo;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use function array_pop;
use function explode;
use function str_replace;
use function trim;
use const DIRECTORY_SEPARATOR;

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

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(FileWriterInterface $fileWriter, string $namespace, ?OutputInterface $output = null)
    {
        $this->fileWriter = $fileWriter;
        $this->namespace = $namespace;
        $this->output = $output;
    }

    public function write(array $schemaFiles, string $extension, string $outputDirectory, bool $asInterface = false): int
    {
        $numberOfGeneratedFiles = 0;
        $fileExtension = $extension;
        $progressBar = null;

        if ($this->output) {
            $progressBar = new ProgressBar($this->output, count($schemaFiles));
            $progressBar->start();
        }

        foreach ($schemaFiles as $schemaFilename => $bundleSchema) {
            if ($this->shouldGenerateInterface($bundleSchema, $asInterface)) {
                continue;
            }

            if ($asInterface) {
                $fileExtension = 'Interface' . $extension;
            }
            $filename = $this->generateFilename($bundleSchema, $fileExtension, $outputDirectory);

            $this->writePopo($bundleSchema, $filename);
            $numberOfGeneratedFiles++;

            if ($progressBar) {
                $progressBar->advance();
            }
        }

        if ($progressBar) {
            $progressBar->finish();
        }

        return $numberOfGeneratedFiles;
    }

    protected function generateFilename(
        BundleSchemaInterface $bundleSchema,
        string $extension,
        string $outputDirectory
    ): SplFileInfo
    {
        $filename = $this->getOutputFilename($bundleSchema, $extension);
        $dir = $this->getOutputDirectory($outputDirectory);

        return new SplFileInfo($dir . $filename);
    }

    protected function getOutputDirectory(string $outputDirectory): string
    {
        $outputDirectory = trim($outputDirectory);
        $outputDirectory = str_replace('\\', DIRECTORY_SEPARATOR, $outputDirectory);

        return $outputDirectory;
    }

    protected function getOutputFilename(BundleSchemaInterface $bundleSchema, string $extension): string
    {
        $nameTokens = explode('\\', $bundleSchema->getSchema()->getName());
        $name = array_pop($nameTokens);
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
        $nameTokens = explode('\\', $bundleSchema->getSchema()->getName());
        $name = array_pop($nameTokens);

        return $this->namespace . '\\' . $name;
    }

    protected function shouldGenerateInterface(BundleSchemaInterface $bundleSchema, bool $asInterface): bool
    {
        return $bundleSchema->getSchema()->isAbstract() && $asInterface;
    }
}
