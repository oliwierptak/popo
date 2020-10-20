<?php

declare(strict_types = 1);

namespace Popo\Model\Helper;

use Popo\Configurator;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationTable
{
    protected OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function showConfigurationTable(Configurator $configurator): void
    {
        if (!$configurator->getModelHelperConfigurator()->isShowConfiguration()) {
            return;
        }

        $table = new Table($this->output->section());
        $table->setStyle('compact');

        if ($configurator->getModelHelperConfigurator()->isShowBorder()) {
            $table->setStyle('default');
        }

        $table
            ->setRows([
                [new TableCell(sprintf('<options=bold>%s</>', $configurator->getConfigName()), ['colspan' => 2])],
                new TableSeparator(),
                ['schema', $configurator->getSchemaDirectory()],
                ['template', $configurator->getTemplateDirectory()],
                ['output', $configurator->getOutputDirectory()],
                ['namespace', $configurator->getNamespace()],
                ['extends', $configurator->getExtends()],
                new TableSeparator(),
                ['extension', $configurator->getExtension()],
                ['returnType', $configurator->getReturnType()],
                new TableSeparator(),
                ['abstract', (int)$configurator->getIsAbstract()],
                ['withInterface', (int)$configurator->getWithInterface()],
            ]);

        $table->render();
    }
}
