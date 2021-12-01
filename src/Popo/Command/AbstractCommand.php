<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoDefinesInterface;
use Popo\PopoFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class AbstractCommand extends Command
{
    protected const COMMAND_NAME = 'unknown';

    protected const COMMAND_DESCRIPTION = 'unknown';

    protected PopoFacade $facade;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): int;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->facade = new PopoFacade();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<fg=yellow>POPO</> <fg=green>v%s</>', PopoDefinesInterface::VERSION));

        try {
            return $this->executeCommand($input, $output);
        } catch (Throwable $exception) {
            $output->writeln(sprintf('<fg=red>ERROR</> <fg=white>%s</>', $exception->getMessage()));
            return 1;
        }
    }
}
