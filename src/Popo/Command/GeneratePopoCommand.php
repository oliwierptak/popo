<?php

declare(strict_types = 1);

namespace Popo\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePopoCommand extends AbstractCommand
{
    const COMMAND_NAME = 'popo';
    const COMMAND_DESCRIPTION = 'Generate POPO files';

    protected function executeCommand(InputInterface $input, OutputInterface $output): ?int
    {
        $configurator = $this->buildConfigurator($input);
        $this->getFacade()->generatePopo($configurator);

        return 0;
    }
}
