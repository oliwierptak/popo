<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoDefinesInterface;
use Popo\PopoFacade;
use Popo\PopoFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    protected const COMMAND_NAME = 'unknown';

    protected const COMMAND_DESCRIPTION = 'unknown';

    protected PopoFacade $facade;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): int;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $factory = new PopoFactory();

        $this->facade = new PopoFacade();
        $this->facade->setFactory($factory);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<fg=yellow>POPO</> <fg=green>v%s</>', PopoDefinesInterface::VERSION));

        return $this->executeCommand($input, $output);
    }
}
