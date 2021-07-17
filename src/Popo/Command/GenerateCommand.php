<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function implode;

class GenerateCommand extends AbstractCommand
{
    const COMMAND_NAME = 'generate';

    const COMMAND_DESCRIPTION = 'Generate POPO files';

    const OPTION_SCHEMA_PATH = 'schemaPath';

    const OPTION_SCHEMA_PATH_FILTER = 'schemaPathFilter';

    const OPTION_SCHEMA_CONFIG_FILENAME = 'schemaConfigFilename';

    const OPTION_OUTPUT_PATH = 'outputPath';

    const OPTION_NAMESPACE = 'namespace';

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition(
                [
                    new InputOption(
                        static::OPTION_SCHEMA_PATH,
                        's',
                        InputOption::VALUE_REQUIRED,
                        'Path to schema file or directory',
                        'popo.yml'
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_CONFIG_FILENAME,
                        'c',
                        InputOption::VALUE_OPTIONAL,
                        'Path to shared schema configuration',
                        ''
                    ),
                    new InputOption(
                        static::OPTION_OUTPUT_PATH,
                        'o',
                        InputOption::VALUE_OPTIONAL,
                        'Output path where the files will be generated. Overrides schema settings when set.',
                        ''
                    ),
                    new InputOption(
                        static::OPTION_NAMESPACE,
                        'm',
                        InputOption::VALUE_OPTIONAL,
                        'Namespace of generated POPO files. Overrides schema settings when set.',
                        ''
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_PATH_FILTER,
                        'p',
                        InputOption::VALUE_OPTIONAL,
                        'Path filter to match POPO schema files.',
                        ''
                    )
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $configurator = $this->buildConfigurator($input);

        $output->writeln('Generating POPO files... ');

        $result = $this->facade->generate($configurator);

        $output->writeln(implode("\n", $result->getGeneratedFiles()));
        $output->writeln('All done.');

        return 0;
    }

    protected function buildConfigurator(InputInterface $input): PopoConfigurator
    {
        return (new PopoConfigurator())
            ->setOutputPath(
                $input->hasOption(static::OPTION_OUTPUT_PATH) ? $input->getOption(static::OPTION_OUTPUT_PATH) : null
            )
            ->setNamespace(
                $input->hasOption(static::OPTION_NAMESPACE) ? $input->getOption(static::OPTION_NAMESPACE) : null
            )
            ->setSchemaPath($input->getOption(static::OPTION_SCHEMA_PATH))
            ->setSchemaPathFilter(
                $input->hasOption(static::OPTION_SCHEMA_PATH_FILTER) ? $input->getOption(static::OPTION_SCHEMA_PATH_FILTER) : null
            )
            ->setSchemaConfigFilename(
                $input->hasOption(static::OPTION_SCHEMA_CONFIG_FILENAME) ? $input->getOption(static::OPTION_SCHEMA_CONFIG_FILENAME) : null
            );
    }
}
