<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Configurator;
use Popo\GeneratorResult;
use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\SchemaBuilder;
use Popo\Schema\SchemaMerger;
use Popo\Writer\FileWriter;
use SplFileInfo;
use Symfony\Component\Console\Helper\ProgressBar;

class Popo
{
    protected SchemaBuilder $schemaBuilder;
    protected SchemaMerger $schemaMerger;
    protected FileWriter $popoWriter;
    protected FileWriter $dtoWriter;

    public function __construct(SchemaBuilder $schemaBuilder, SchemaMerger $schemaMerger, FileWriter $popoWriter, FileWriter $dtoWriter)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaMerger = $schemaMerger;
        $this->popoWriter = $popoWriter;
        $this->dtoWriter = $dtoWriter;
    }

    public function generate(Configurator $configurator): GeneratorResult
    {
        $this->validatePaths($configurator);

        $numberOfGeneratedFiles = 0;
        $result = new GeneratorResult();

        $schemaFiles = $this->schemaBuilder->build($configurator);
        $mergedSchemaFiles = $this->schemaMerger->merge($schemaFiles);

        $progressBar = new ProgressBar($configurator->getOutput()->section(), count($mergedSchemaFiles));

        foreach ($mergedSchemaFiles as $schemaFilename => $bundleSchema) {
            $this->updateSchema($bundleSchema, $configurator);

            $filename = $this->generateFilename($bundleSchema, $configurator);
            $this->writePopo($bundleSchema, $configurator, $filename);

            $bundleSchema->getSchema()->setExtension('Interface.php');
            $filename = $this->generateFilename($bundleSchema, $configurator);
            $this->writeDto($bundleSchema, $configurator, $filename);

            $numberOfGeneratedFiles++;

            $progressBar->advance();
        }

        $result->setFileCount(count($mergedSchemaFiles));

        $progressBar->finish();

        $configurator->getOutput()->writeln('');

        return $result;
    }

    protected function validatePaths(Configurator $configurator): void
    {
    }

    protected function generateFilename(BundleSchema $bundleSchema, Configurator $configurator): SplFileInfo
    {
        $filename = $this->generateOutputFilename($bundleSchema);
        $dir = $this->generateOutputDirectory($configurator->getOutputDirectory());

        return new SplFileInfo($dir . $filename);
    }

    protected function generateOutputDirectory(string $outputDirectory): string
    {
        $outputDirectory = trim($outputDirectory);
        $outputDirectory = str_replace('\\', DIRECTORY_SEPARATOR, $outputDirectory);

        return $outputDirectory;
    }

    protected function generateOutputFilename(BundleSchema $bundleSchema): string
    {
        $nameTokens = explode('\\', $bundleSchema->getSchema()->getName());
        $name = array_pop($nameTokens);
        $name .= $bundleSchema->getSchema()->getExtension();

        return $name;
    }

    protected function writePopo(BundleSchema $bundleSchema, Configurator $configurator, SplFileInfo $filename): void
    {
        $name = $this->generateProjectSchemaName($bundleSchema, $configurator);

        $bundleSchema
            ->getSchema()
            ->setName($name);

        $this->popoWriter->write($filename->getPathname(), $bundleSchema->getSchema());
    }

    protected function writeDto(BundleSchema $bundleSchema, Configurator $configurator, SplFileInfo $filename): void
    {
        if (!$this->shouldGenerateInterface($bundleSchema)) {
            return;
        }

        $name = $this->generateProjectSchemaName($bundleSchema, $configurator);

        $bundleSchema
            ->getSchema()
            ->setName($name);

        $this->dtoWriter->write($filename->getPathname(), $bundleSchema->getSchema());
    }

    protected function generateProjectSchemaName(BundleSchema $bundleSchema, Configurator $configurator): string
    {
        $nameTokens = explode('\\', $bundleSchema->getSchema()->getName());
        $name = array_pop($nameTokens);

        return $configurator->getNamespace() . '\\' . $name;
    }

    protected function shouldGenerateInterface(BundleSchema $bundleSchema): bool
    {
        return !$bundleSchema->getSchema()->isAbstract() && $bundleSchema->getSchema()->getWithInterface();
    }

    protected function updateSchema(BundleSchema $bundleSchema, Configurator $configurator): BundleSchema
    {
        $extends = trim((string)$configurator->getExtends());
        if ($extends !== '') {
            $bundleSchema
                ->getSchema()
                ->setExtends($extends);
        }

        return $bundleSchema;
    }
}
