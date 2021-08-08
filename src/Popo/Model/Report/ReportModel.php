<?php

declare(strict_types = 1);

namespace Popo\Model\Report;

use Popo\Loader\SchemaLoader;
use Popo\PopoConfigurator;
use Popo\Schema\SchemaFile;
use function strcasecmp;
use function uksort;

class ReportModel
{
    public function __construct(protected SchemaLoader $loader)
    {
    }

    public function generate(PopoConfigurator $configurator): ReportResult
    {
        $data = $this->loader->loadReport($configurator);
        $sharedSchemaFile = $this->loader->loadSharedConfig($configurator->getSchemaConfigFilename());

        return $this->generateReport($data, $sharedSchemaFile);
    }

    /**
     * @param SchemaFile[] $data
     * @param \Popo\Schema\SchemaFile $sharedSchemaFile
     *
     * @return \Popo\Model\Report\ReportResult
     */
    protected function generateReport(array $data, SchemaFile $sharedSchemaFile): ReportResult
    {
        $result = new ReportResult;
        $sharedFileConfig = $sharedSchemaFile->getFileConfig()['property'] ?? [];
        $sharedSchemaFileConfig = [];

        foreach ($sharedFileConfig as $dataItem) {
            $result->add(
                (new ReportResultItem())
                    ->setName($dataItem['name'])
                    ->setData($dataItem)
                    ->setSchemaFilename($sharedSchemaFile->getFilename()->getPathname())
                    ->markAsFileConfig()
            );
        }

        foreach ($data as $schemaFile) {
            $fileConfig = $schemaFile->getFileConfig()['property'] ?? [];
            foreach ($fileConfig as $dataItem) {
                $result->add(
                    (new ReportResultItem())
                        ->setName($dataItem['name'])
                        ->setData($dataItem)
                        ->setSchemaFilename($schemaFile->getFilename()->getPathname())
                        ->markAsFileConfig()
                );
            }

            foreach ($schemaFile->getData() as $schemaName => $popoCollection) {
                $schemaConfig = $schemaFile->getSchemaConfig()[$schemaName]['property'] ?? [];
                $sharedSchemaFileConfig[$schemaName] = $sharedSchemaFile
                        ->getSchemaConfig()[$schemaName]['property'] ?? [];

                foreach ($schemaConfig as $dataItem) {
                    $result->add(
                        (new ReportResultItem())
                            ->setName($dataItem['name'])
                            ->setData($dataItem)
                            ->setSchemaName($schemaName)
                            ->setSchemaFilename($schemaFile->getFilename()->getPathname())
                            ->markAsSchemaConfig()
                    );
                }

                foreach ($popoCollection as $popoName => $popoData) {
                    foreach ($popoData['property'] as $propertyData) {
                        $result->add(
                            (new ReportResultItem())
                                ->setName($propertyData['name'])
                                ->setData($propertyData)
                                ->setPopoName($popoName)
                                ->setSchemaName($schemaName)
                                ->setSchemaFilename($schemaFile->getFilename()->getPathname())
                                ->markAsPropertyConfig()
                        );
                    }
                }
            }
        }

        foreach ($sharedSchemaFileConfig as $schemaName => $items) {
            foreach ($items as $dataItem) {
                $result->add((new ReportResultItem())
                    ->setName($dataItem['name'])
                    ->setData($dataItem)
                    ->setSchemaName($schemaName)
                    ->setSchemaFilename($sharedSchemaFile->getFilename()->getPathname())
                    ->markAsSchemaConfig()
                );
            }
        }

        return $this->sortProperties($result);
    }

    protected function sortProperties(ReportResult $result): ReportResult
    {
        $data = $result->getData();
        ksort($data);

        foreach ($data as $propertyName => $reportItems) {
            uksort($data[$propertyName], function ($a, $b) use ($data, $propertyName) {
                $schemaNameValue = strcasecmp(
                    (string) $data[$propertyName][$a]->getSchemaName(),
                    (string) $data[$propertyName][$b]->getSchemaName()
                );
                $nameValue = strcasecmp(
                    (string) $data[$propertyName][$a]->getName(),
                    (string) $data[$propertyName][$b]->getName()
                );

                return $schemaNameValue + $nameValue;
            });
        }

        $result->setData($data);

        return $result;
    }
}
