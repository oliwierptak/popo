<?php declare(strict_types = 1);

namespace Popo\Model;

use InvalidArgumentException;
use Popo\Configurator;
use Popo\GeneratorResult;
use Popo\Model\Helper\ProgressIndicator;
use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\SchemaBuilder;
use Popo\Schema\SchemaMerger;
use Popo\Writer\SchemaWriter;
use SplFileInfo;
use function is_dir;
use function sprintf;

class Popo
{
    protected SchemaBuilder $schemaBuilder;
    protected SchemaMerger $schemaMerger;
    protected SchemaWriter $schemaWriter;
    protected ProgressIndicator $progressIndicator;

    public function __construct(
        SchemaBuilder $schemaBuilder,
        SchemaMerger $schemaMerger,
        SchemaWriter $popoWriter,
        ProgressIndicator $progressIndicator
    ) {
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaMerger = $schemaMerger;
        $this->schemaWriter = $popoWriter;
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

        $result = new GeneratorResult();
        $schemaFiles = $this->schemaBuilder->build($configurator);
        $mergedSchemaFiles = $this->schemaMerger->merge($schemaFiles);

        $this->progressIndicator->start(count($mergedSchemaFiles));

        foreach ($mergedSchemaFiles as $schemaFilename => $bundleSchema) {
            $bundleSchema = $this->updateSchema($bundleSchema, $configurator);

            $this->generatePopo($bundleSchema, $configurator);
            $this->generateDto($bundleSchema, $configurator);

            $this->progressIndicator->advance();
            $result->grow();
        }

        $this->progressIndicator->stop($result->getFileCount());

        return $result;
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
        $requiredPaths = [
            'schema' => $configurator->getSchemaDirectory(),
            'output' => $configurator->getOutputDirectory(),
            'template' => $configurator->getTemplateDirectory(),
        ];

        foreach ($requiredPaths as $type => $path) {
            if (!is_dir($path)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Required %s directory does not exist under path: %s',
                        $type,
                        $path
                    )
                );
            }
        }
    }

    protected function generatePopo(BundleSchema $bundleSchema, Configurator $configurator): void
    {
        if ($bundleSchema->getSchema()->isWithIPopo()) {
            $currentBundleSchema = clone $bundleSchema;
            $filename = $this->generateFilename($currentBundleSchema, $configurator);
            $this->writePopo($currentBundleSchema, $configurator, $filename);
        }
    }

    protected function generateDto(BundleSchema $bundleSchema, Configurator $configurator): void
    {
        if ($this->shouldGenerateInterface($bundleSchema)) {
            $currentBundleSchema = clone $bundleSchema;
            $currentBundleSchema->getSchema()->setIsWithInterface(true);
            $currentBundleSchema->getSchema()->setExtension('Interface.php');
            $filename = $this->generateFilename($currentBundleSchema, $configurator);
            $this->writeDto($currentBundleSchema, $configurator, $filename);
        }
    }

    protected function updateSchema(BundleSchema $bundleSchema, Configurator $configurator): BundleSchema
    {
        $extends = trim((string) $configurator->getExtends());
        if ($extends !== '') {
            $bundleSchema
                ->getSchema()
                ->setExtends($extends);
        }

        return $bundleSchema;
    }

    protected function generateFilename(BundleSchema $bundleSchema, Configurator $configurator): SplFileInfo
    {
        $filename = $this->generateOutputFilename($bundleSchema);
        $dir = $this->generateOutputDirectory($configurator->getOutputDirectory());

        return new SplFileInfo($dir . $filename);
    }

    protected function generateOutputFilename(BundleSchema $bundleSchema): string
    {
        $nameTokens = explode('\\', $bundleSchema->getSchema()->getName());
        $name = array_pop($nameTokens);
        $name .= $bundleSchema->getSchema()->getExtension();

        return $name;
    }

    protected function generateOutputDirectory(string $outputDirectory): string
    {
        $outputDirectory = trim($outputDirectory);
        $outputDirectory = str_replace('\\', DIRECTORY_SEPARATOR, $outputDirectory);

        return $outputDirectory;
    }

    protected function writePopo(BundleSchema $bundleSchema, Configurator $configurator, SplFileInfo $filename): void
    {
        $bundleSchemaToWrite = clone $bundleSchema;
        $name = $this->generateProjectSchemaName($bundleSchemaToWrite, $configurator);

        $bundleSchemaToWrite
            ->getSchema()
            ->setName($name);

        if ($bundleSchemaToWrite->getSchema()->isAbstract()) {
            $this->schemaWriter->writeAbstract($filename->getPathname(), $bundleSchemaToWrite->getSchema());

            return;
        }

        $this->schemaWriter->writePopo($filename->getPathname(), $bundleSchema->getSchema());
    }

    protected function writeDto(BundleSchema $bundleSchema, Configurator $configurator, SplFileInfo $filename): void
    {
        $bundleSchemaToWrite = clone $bundleSchema;
        $name = $this->generateProjectSchemaName($bundleSchemaToWrite, $configurator);

        $bundleSchemaToWrite
            ->getSchema()
            ->setName($name);

        $this->schemaWriter->writeDto($filename->getPathname(), $bundleSchemaToWrite->getSchema());
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
}
