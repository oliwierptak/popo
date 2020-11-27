<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\Configurator;
use Popo\Model\Helper\ConfigurationTable;
use Popo\Model\Helper\ModelHelperConfigurator;
use Popo\PopoFacade;
use Popo\PopoFacadeInterfaces;
use Popo\PopoFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    const COMMAND_NAME = 'unknown';
    const COMMAND_DESCRIPTION = 'unknown';

    protected ?PopoFacadeInterfaces $facade;

    protected ?ConfigurationTable $configurationTable;

    protected Configurator $configurator;

    protected ModelHelperConfigurator $modelHelperConfigurator;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): int;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->setupModelHelperConfigurator($output);
        $this->configurationTable = new ConfigurationTable($output);

        $factory = new PopoFactory();
        $factory->setOutput($output);

        $this->facade = new PopoFacade();
        $this->facade->setFactory($factory);
    }

    protected function setupModelHelperConfigurator(OutputInterface $output): void
    {
        $this->modelHelperConfigurator = new ModelHelperConfigurator();

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL) {
            $this->modelHelperConfigurator
                ->setShowConfiguration(false)
                ->setShowProgressBar(false);
        }

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) {
            $this->modelHelperConfigurator
                ->setShowConfiguration(true)
                ->setShowProgressBar(false);
        }

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $this->modelHelperConfigurator
                ->setShowConfiguration(true)
                ->setShowProgressBar(true);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->modelHelperConfigurator->isShowConfiguration()
            || $this->modelHelperConfigurator->isShowProgressBar()) {
            $output->writeln('');
            $output->writeln(sprintf('<fg=yellow>POPO</> <fg=green>v%s</>', PopoFacadeInterfaces::VERSION));
            $output->writeln('');
        }

        return $this->executeCommand($input, $output);
    }
}
