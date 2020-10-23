<?php

declare(strict_types = 1);

namespace Popo\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    const COMMAND_NAME = 'generate';
    const COMMAND_DESCRIPTION = 'Generate POPO files';

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->buildConfigurator($input, $output);
        $items = $config->getItemsToGenerate();

        foreach ($items as $name => $configItem) {
            $configItem->getConfigurator()->setModelHelperConfigurator($this->modelHelperConfigurator);
            $this->configurationTable->showConfigurationTable($configItem->getConfigurator());
            $this->facade->generate($configItem->getConfigurator());
        }

        return 0;
    }
}
