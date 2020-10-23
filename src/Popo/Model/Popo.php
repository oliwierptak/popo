<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Configurator;
use Popo\GeneratorResult;
use Popo\Model\Helper\ProgressIndicator;
use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\SchemaBuilder;
use Popo\Schema\SchemaMerger;
use Popo\Writer\FileWriter;
use SplFileInfo;

class Popo
{
    protected SchemaBuilder $schemaBuilder;

    protected SchemaMerger $schemaMerger;

    protected FileWriter $popoWriter;

    protected FileWriter $dtoWriter;

    protected FileWriter $abstractWriter;

    protected ProgressIndicator $progressIndicator;

    public function __construct(SchemaBuilder $schemaBuilder, SchemaMerger $schemaMerger, FileWriter $popoWriter, FileWriter $dtoWriter, FileWriter $abstractWriter, ProgressIndicator $progressIndicator)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaMerger = $schemaMerger;
        $this->popoWriter = $popoWriter;
        $this->dtoWriter = $dtoWriter;
        $this->abstractWriter = $abstractWriter;
        $this->progressIndicator = $progressIndicator;
    }

    /**
     * @param Configurator $configurator
     *
     * @return GeneratorResult
     * @throws \InvalidArgumentException
     */
    public function generate(Configurator $configurator): GeneratorResult
    {
        $this->assertConfiguration($configurator);

        $numberOfGeneratedFiles = 0;
        $result = new GeneratorResult();

        $schemaFiles = $this->schemaBuilder->build($configurator);
        $mergedSchemaFiles = $this->schemaMerger->merge($schemaFiles);

        $this->progressIndicator->start();

        foreach ($mergedSchemaFiles as $schemaFilename => $bundleSchema) {
            $bundleSchema = $this->updateSchema($bundleSchema, $configurator);

            if ($bundleSchema->getSchema()->isWithIPopo()) {
                $currentBundleSchema = clone $bundleSchema;
                $filename = $this->generateFilename($currentBundleSchema, $configurator);
                $this->writePopo($currentBundleSchema, $configurator, $filename);
            }

            if ($this->shouldGenerateInterface($bundleSchema)) {
                $currentBundleSchema = clone $bundleSchema;
                $currentBundleSchema->getSchema()->setIsWithInterface(true);
                $currentBundleSchema->getSchema()->setExtension('Interface.php');
                $filename = $this->generateFilename($currentBundleSchema, $configurator);
                $this->writeDto($currentBundleSchema, $configurator, $filename);
            }

            $numberOfGeneratedFiles++;

            $this->progressIndicator->advance();
        }

        $result->setFileCount(count($mergedSchemaFiles));

        $this->progressIndicator->stop();

        return $result;
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

        if ($bundleSchema->getSchema()->isAbstract()) {
            $this->abstractWriter->write($filename->getPathname(), $bundleSchema->getSchema());
            return;
        }

        $this->popoWriter->write($filename->getPathname(), $bundleSchema->getSchema());
    }

    protected function writeDto(BundleSchema $bundleSchema, Configurator $configurator, SplFileInfo $filename): void
    {
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
        return !$bundleSchema->getSchema()->isAbstract() && $bundleSchema->getSchema()->isWithInterface();
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

    /**
     * @param \Popo\Configurator $configurator
     *
     * @return void
     * @throws \InvalidArgumentException
     *
     */
    protected function assertConfiguration(Configurator $configurator): void
    {
        $schemaDirectory = $configurator->getSchemaDirectory();
        $outputDirectory = $configurator->getOutputDirectory();
        $templateDirectory = $configurator->getTemplateDirectory();

        $requiredPaths = [
            'schema' => $schemaDirectory,
            'output' => $outputDirectory,
            'template' => $templateDirectory,
        ];

        foreach ($requiredPaths as $type => $path) {
            if (!\is_dir($path)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Required %s directory does not exist under path: %s',
                    $type,
                    $path
                ));
            }
        }
    }
}
