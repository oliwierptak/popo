<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function implode;

class ReportCommand extends AbstractCommand
{
    public const COMMAND_NAME = 'report';

    public const COMMAND_DESCRIPTION = 'Report information about POPO schema configuration';

    public const OPTION_SCHEMA_PATH = 'schemaPath';

    public const OPTION_SCHEMA_PATH_FILTER = 'schemaPathFilter';

    public const OPTION_SCHEMA_CONFIG_FILENAME = 'schemaConfigFilename';

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
                        null
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_PATH_FILTER,
                        'p',
                        InputOption::VALUE_OPTIONAL,
                        'Path filter to match POPO schema files.',
                        null
                    )
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        if ($output->getVerbosity() <= OutputInterface::VERBOSITY_QUIET) {
            return 0;
        }

        $configurator = $this->buildConfigurator($input);
        $output->writeln('POPO Schema Report');
        $result = $this->facade->report($configurator);

        foreach ($result->getData() as $schemaName => $popoCollection) {
            foreach ($popoCollection as $popoName => $propertyCollection) {
                foreach ($propertyCollection as $propertyName => $propertyLocations) {
                    $colorSchemaName = (count($propertyLocations) === 1) ? 'yellow' : 'bright-yellow';
                    $colorPropertyName = (count($propertyLocations) === 1) ? 'yellow' : 'bright-yellow';

                    $output->writeln(sprintf(
                        "<fg=${colorSchemaName}>%s::%s::</><fg=${colorPropertyName}>%s</>",
                        $schemaName,
                        $popoName,
                        $propertyName,
                    ));

                    foreach ($propertyLocations as $index => $location) {
                        $tokens = explode(':',$location);
                        $output->writeln(sprintf(
                            ' - <fg=cyan>%s</><fg=gray>:</> <fg=green>%s</>',
                            $tokens[0],
                            $tokens[1],
                        ));
                    }

                    $output->writeln('');
                }
            }
        }

        return 0;
    }

    protected function buildConfigurator(InputInterface $input): PopoConfigurator
    {
        return (new PopoConfigurator())
            ->setSchemaPath($input->getOption(static::OPTION_SCHEMA_PATH))
            ->setSchemaPathFilter(
                $input->hasOption(static::OPTION_SCHEMA_PATH_FILTER) ? $input->getOption(static::OPTION_SCHEMA_PATH_FILTER) : null
            )
            ->setSchemaConfigFilename(
                $input->hasOption(static::OPTION_SCHEMA_CONFIG_FILENAME) ? $input->getOption(static::OPTION_SCHEMA_CONFIG_FILENAME) : null
            );
    }
}
