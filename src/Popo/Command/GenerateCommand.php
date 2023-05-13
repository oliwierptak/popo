<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Command\Command;
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

    public const OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER = 'ignoreNonExistingSchemaFolder';

    public const OPTION_CLASS_PLUGIN_COLLECTION = 'classPluginCollection';

    public const OPTION_MAPPING_POLICY_PLUGIN_COLLECTION = 'mappingPolicyPluginCollection';

    public const OPTION_NAMESPACE_PLUGIN_COLLECTION = 'namespacePluginCollection';

    public const OPTION_PHP_FILE_PLUGIN_COLLECTION = 'phpFilePluginCollection';

    public const OPTION_PROPERTY_PLUGIN_COLLECTION = 'propertyPluginCollection';

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
                    new InputOption(
                        static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER,
                        'ig',
                        InputOption::VALUE_OPTIONAL,
                        'When set, an exception will not be thrown in case missing schemaPath folder',
                        false
                    ),
                    new InputOption(
                        static::OPTION_CLASS_PLUGIN_COLLECTION,
                        'clp',
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                        'Collection of class names for plugins implementing \Popo\Plugin\ClassPluginInterface',
                        []
                    ),
                    new InputOption(
                        static::OPTION_MAPPING_POLICY_PLUGIN_COLLECTION,
                        'mpp',
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                        'Collection of class names for plugins implementing \Popo\Plugin\MappingPolicyPluginInterface',
                        []
                    ),
                    new InputOption(
                        static::OPTION_NAMESPACE_PLUGIN_COLLECTION,
                        'nsp',
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                        'Collection of class names for plugins implementing \Popo\Plugin\NamespacePluginInterface',
                        []
                    ),
                    new InputOption(
                        static::OPTION_PHP_FILE_PLUGIN_COLLECTION,
                        'pfp',
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                        'Collection of class names for plugins implementing \Popo\Plugin\PhpFilePluginInterface',
                        []
                    ),
                    new InputOption(
                        static::OPTION_PROPERTY_PLUGIN_COLLECTION,
                        'ppp',
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                        'Collection of class names for plugins implementing \Popo\Plugin\PropertyPluginInterface',
                        []
                    ),
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
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

        return Command::SUCCESS;
    }

    protected function buildConfigurator(InputInterface $input): PopoConfigurator
    {
        $configurator = (new PopoConfigurator())
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
            )
            ->setShouldIgnoreNonExistingSchemaFolder(
                (bool) ($input->hasOption(static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER) ? $input->getOption(
                    static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER
                ) : false)
            );

        $pluginCollection = $input->hasOption(static::OPTION_CLASS_PLUGIN_COLLECTION)
            ? $input->getOption(static::OPTION_CLASS_PLUGIN_COLLECTION) : [];
        foreach ($pluginCollection as $pluginClassName) {
            $this->facade->addClassPluginClassName($pluginClassName);
        }

        $pluginCollection = $input->hasOption(static::OPTION_MAPPING_POLICY_PLUGIN_COLLECTION)
            ? $input->getOption(static::OPTION_MAPPING_POLICY_PLUGIN_COLLECTION) : [];
        foreach ($pluginCollection as $classPluginClassName) {
            $this->facade->addMappingPolicyPluginClassName($classPluginClassName);
        }

        $pluginCollection = $input->hasOption(static::OPTION_NAMESPACE_PLUGIN_COLLECTION)
            ? $input->getOption(static::OPTION_NAMESPACE_PLUGIN_COLLECTION) : [];
        foreach ($pluginCollection as $classPluginClassName) {
            $this->facade->addNamespacePluginClassName($classPluginClassName);
        }

        $pluginCollection = $input->hasOption(static::OPTION_PHP_FILE_PLUGIN_COLLECTION)
            ? $input->getOption(static::OPTION_PHP_FILE_PLUGIN_COLLECTION) : [];
        foreach ($pluginCollection as $classPluginClassName) {
            $this->facade->addPhpFilePluginClassName($classPluginClassName);
        }

        $pluginCollection = $input->hasOption(static::OPTION_PROPERTY_PLUGIN_COLLECTION)
            ? $input->getOption(static::OPTION_PROPERTY_PLUGIN_COLLECTION) : [];
        foreach ($pluginCollection as $classPluginClassName) {
            $this->facade->addPropertyPluginClassName($classPluginClassName);
        }

        return $this->facade->reconfigure($configurator);
    }
}
