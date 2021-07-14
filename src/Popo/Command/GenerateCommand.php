<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    const COMMAND_NAME = 'generate';

    const COMMAND_DESCRIPTION = 'Generate POPO files';

    const OPTION_SCHEMA_PATH = 'schemaPath';

    const OPTION_OUTPUT_PATH = 'outputPath';

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition(
                [
                    new InputOption(
                        static::OPTION_OUTPUT_PATH,
                        'o',
                        InputOption::VALUE_REQUIRED,
                        'Output path where the files will be generated',
                        ''
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_PATH,
                        's',
                        InputOption::VALUE_REQUIRED,
                        'Path to schema file or directory',
                        'popo.yml'
                    ),
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $configurator = $this->buildConfigurator($input, $output);

        $output->writeln('Generating POPO files... ');

        $result = $this->facade->generate($configurator);

        $output->writeln(\implode("\n", $result->getGeneratedFiles()));
        $output->writeln('All done.');

        return 0;
    }

    protected function buildConfigurator(InputInterface $input, OutputInterface $output): PopoConfigurator
    {
        return (new PopoConfigurator())
            ->setOutputPath($input->getOption(static::OPTION_OUTPUT_PATH))
            ->setSchemaPath($input->getOption(static::OPTION_SCHEMA_PATH));
    }
}
