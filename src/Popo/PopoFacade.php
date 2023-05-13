<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\Generate\GenerateResult;
use Popo\Model\Report\ReportResult;

class PopoFacade implements PopoFacadeInterface
{
    protected PopoFactory $factory;

    protected function getFactory(): PopoFactory
    {
        if (empty($this->factory)) {
            $this->factory = new PopoFactory();
        }

        return $this->factory;
    }

    public function setFactory(PopoFactory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    public function generate(PopoConfigurator $configurator): GenerateResult
    {
        return $this->getFactory()
            ->createPopoModel($configurator)
            ->generate($configurator);
    }

    public function addClassPluginClassName(string $classPluginClassName): self
    {
        $this->getFactory()
            ->createExternalPluginContainer()
            ->addClassPluginClassName($classPluginClassName);

        return $this;
    }

    public function addMappingPolicyPluginClassName(string $mappingPolicyPluginClassName): self
    {
        $this->getFactory()
            ->createExternalPluginContainer()
            ->addMappingPolicyPluginClassName($mappingPolicyPluginClassName);

        return $this;
    }

    public function addNamespacePluginClassName(string $namespacePluginClassName): self
    {
        $this->getFactory()
            ->createExternalPluginContainer()
            ->addNamespacePluginClassName($namespacePluginClassName);

        return $this;
    }

    public function addPhpFilePluginClassName(string $phpFilePluginClassName): self
    {
        $this->getFactory()
            ->createExternalPluginContainer()
            ->addPhpFlePluginClassName($phpFilePluginClassName);

        return $this;
    }

    public function addPropertyPluginClassName(string $propertyPluginClassName): self
    {
        $this->getFactory()
            ->createExternalPluginContainer()
            ->addPropertyPluginClassName($propertyPluginClassName);

        return $this;
    }

    public function reconfigure(PopoConfigurator $configurator): PopoConfigurator
    {
        return $this->getFactory()
            ->createExternalPluginContainer()
            ->reconfigure($configurator);
    }

    public function report(PopoConfigurator $configurator): ReportResult
    {
        return $this->getFactory()
            ->createReportModel()
            ->generate($configurator);
    }
}
