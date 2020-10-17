<?php

declare(strict_types = 1);

namespace Popo\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDtoCommand extends AbstractCommand
{
    const COMMAND_NAME = 'dto';
    const COMMAND_DESCRIPTION = 'Generate DTO files';

    protected function executeCommand(InputInterface $input, OutputInterface $output): ?int
    {
        $configurator = $this->buildConfigurator($input, $output);
        $this->getFacade()->generateDto($configurator);

        return 0;
    }
}
