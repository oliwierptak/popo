<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReportCommand extends AbstractCommand
{
    public const COMMAND_NAME = 'report';

    protected const COMMAND_DESCRIPTION = 'Report information about POPO schema configuration';

    protected const OPTION_SCHEMA_PATH = 'schemaPath';

    protected const OPTION_SCHEMA_PATH_FILTER = 'schemaPathFilter';

    protected const OPTION_SCHEMA_CONFIG_FILENAME = 'schemaConfigFilename';

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
                    ),
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $configurator = $this->buildConfigurator($input);
        $output->writeln('POPO Schema Report');
        $output->writeln('');

        $result = $this->facade->report($configurator);

        /**
         * @var \Popo\Model\Report\ReportResultItem[] $reportItems
         */
        foreach ($result->getData() as $propertyName => $reportItems) {
            $output->writeln(
                sprintf(
                    "<fg=yellow>%s</>",
                    $propertyName,
                )
            );

            foreach ($reportItems as $reportItem) {
                $output->writeln(
                    sprintf(
                        " <fg=gray>%s</> <fg=green>%s</> - <fg=cyan>%s</>",
                        $reportItem->getType(),
                        $reportItem->getSchemaName() . ($reportItem->getPopoName() ? '::' . $reportItem->getPopoName() : ''),
                        $reportItem->getSchemaFilename()
                    )
                );
            }
        }

        return Command::SUCCESS;
    }

    protected function buildConfigurator(InputInterface $input): PopoConfigurator
    {
        return (new PopoConfigurator())
            ->setSchemaPath($input->getOption(static::OPTION_SCHEMA_PATH))
            ->setSchemaPathFilter(
                $input->hasOption(static::OPTION_SCHEMA_PATH_FILTER) ? $input->getOption(
                    static::OPTION_SCHEMA_PATH_FILTER
                ) : null
            )
            ->setSchemaConfigFilename(
                $input->hasOption(static::OPTION_SCHEMA_CONFIG_FILENAME) ? $input->getOption(
                    static::OPTION_SCHEMA_CONFIG_FILENAME
                ) : null
            );
    }
}
