<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function implode;

class GenerateCommand extends AbstractCommand
{
    public const COMMAND_NAME = 'generate';

    public const COMMAND_DESCRIPTION = 'Generate POPO files';

    public const OPTION_SCHEMA_PATH = 'schemaPath';

    public const OPTION_SCHEMA_PATH_FILTER = 'schemaPathFilter';

    public const OPTION_SCHEMA_CONFIG_FILENAME = 'schemaConfigFilename';

    public const OPTION_SCHEMA_FILENAME_MASK = 'schemaFilenameMask';

    public const OPTION_OUTPUT_PATH = 'outputPath';

    public const OPTION_NAMESPACE = 'namespace';

    public const OPTION_NAMESPACE_ROOT = 'namespaceRoot';

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
                        null
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_CONFIG_FILENAME,
                        'c',
                        InputOption::VALUE_OPTIONAL,
                        'Path to shared schema configuration',
                        null
                    ),
                    new InputOption(
                        static::OPTION_OUTPUT_PATH,
                        'o',
                        InputOption::VALUE_OPTIONAL,
                        'Output path where the files will be generated. Overrides schema settings when set.',
                        null
                    ),
                    new InputOption(
                        static::OPTION_NAMESPACE,
                        'ns',
                        InputOption::VALUE_OPTIONAL,
                        'Namespace of generated POPO files. Overrides schema settings when set.',
                        null
                    ),
                    new InputOption(
                        static::OPTION_NAMESPACE_ROOT,
                        'nr',
                        InputOption::VALUE_OPTIONAL,
                        'Remaps namespace and outputPath',
                        null
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_PATH_FILTER,
                        'p',
                        InputOption::VALUE_OPTIONAL,
                        'Path filter to match POPO schema files.',
                        null
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA_FILENAME_MASK,
                        'm',
                        InputOption::VALUE_OPTIONAL,
                        'Schema filename mask.',
                        '*.popo.yml'
                    ),
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        if ($output->getVerbosity() <= OutputInterface::VERBOSITY_QUIET) {
            return 0;
        }

        $configurator = $this->buildConfigurator($input);
        $output->writeln('Generating POPO files... ');
        $result = $this->facade->generate($configurator);

        $data = [];
        foreach ($result->getGeneratedFiles() as $item) {
            $data[] = sprintf(
                '<fg=yellow>%s:</><fg=green>%s\%s</> -> <fg=green>%s</>',
                $item['schemaName'],
                $item['namespace'],
                $item['popoName'],
                $item['filename'],
            );
        }

        $output->writeln(implode("\n", $data));
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
            ->setNamespaceRoot(
                $input->hasOption(static::OPTION_NAMESPACE_ROOT) ? $input->getOption(
                    static::OPTION_NAMESPACE_ROOT
                ) : null
            )
            ->setSchemaPath((string) $input->getOption(static::OPTION_SCHEMA_PATH))
            ->setSchemaPathFilter(
                $input->hasOption(static::OPTION_SCHEMA_PATH_FILTER) ? $input->getOption(
                    static::OPTION_SCHEMA_PATH_FILTER
                ) : null
            )
            ->setSchemaConfigFilename(
                $input->hasOption(static::OPTION_SCHEMA_CONFIG_FILENAME) ? $input->getOption(
                    static::OPTION_SCHEMA_CONFIG_FILENAME
                ) : null
            )
            ->setSchemaFilenameMask(
                $input->hasOption(static::OPTION_SCHEMA_FILENAME_MASK) ? $input->getOption(
                    static::OPTION_SCHEMA_FILENAME_MASK
                ) : '*.popo.yml'
            );
    }
}
