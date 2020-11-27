<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\Configurator;
use Popo\Configurator\ConfigContainer;
use Popo\Model\Helper\ModelHelperConfigurator;
use Popo\Schema\SchemaConfigurator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    const COMMAND_NAME = 'generate';
    const COMMAND_DESCRIPTION = 'Generate POPO files';
    const ARGUMENT_CONFIG_SECTION_NAME = 'configSectionName';
    const OPTION_SCHEMA = 'schema';
    const OPTION_TEMPLATE = 'template';
    const OPTION_OUTPUT = 'output';
    const OPTION_NAMESPACE = 'namespace';
    const OPTION_EXTENSION = 'extension';
    const OPTION_IS_ABSTRACT = 'abstract';
    const OPTION_EXTENDS = 'extends';
    const OPTION_RETURN_TYPE = 'returnType';
    const OPTION_WITH_POPO = 'withPopo';
    const OPTION_WITH_INTERFACE = 'withInterface';
    const OPTION_CONFIG_FILENAME = 'configFile';

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition(
                [
                    new InputArgument(
                        static::ARGUMENT_CONFIG_SECTION_NAME,
                        InputOption::VALUE_OPTIONAL,
                        'Config section name',
                        []
                    ),
                    new InputOption(
                        static::OPTION_CONFIG_FILENAME,
                        'c',
                        InputOption::VALUE_OPTIONAL,
                        'Config filename',
                        '.popo'
                    ),
                    new InputOption(
                        static::OPTION_SCHEMA, 's', InputOption::VALUE_OPTIONAL, 'Schema directory', 'popo/'
                    ),
                    new InputOption(
                        static::OPTION_TEMPLATE,
                        't',
                        InputOption::VALUE_OPTIONAL,
                        'Template directory',
                        'vendor/popo/generator/templates/'
                    ),
                    new InputOption(
                        static::OPTION_OUTPUT,
                        'o',
                        InputOption::VALUE_OPTIONAL,
                        'Output directory for generated POPO files',
                        'src/Configurator/'
                    ),
                    new InputOption(
                        static::OPTION_NAMESPACE,
                        'm',
                        InputOption::VALUE_OPTIONAL,
                        'Namespace for generated POPO files',
                        'Configurator'
                    ),
                    new InputOption(
                        static::OPTION_EXTENSION,
                        'x',
                        InputOption::VALUE_OPTIONAL,
                        'Extension of generated POPO files',
                        '.php'
                    ),
                    new InputOption(
                        static::OPTION_IS_ABSTRACT,
                        'a',
                        InputOption::VALUE_OPTIONAL,
                        'Setting it to true will generate abstract POPO classes',
                        null
                    ),
                    new InputOption(
                        static::OPTION_EXTENDS,
                        'e',
                        InputOption::VALUE_OPTIONAL,
                        'Which class should the generated classes inherit from',
                        null
                    ),
                    new InputOption(
                        static::OPTION_RETURN_TYPE,
                        'r',
                        InputOption::VALUE_OPTIONAL,
                        'What fromArray(..) method should return',
                        null
                    ),
                    new InputOption(
                        static::OPTION_WITH_POPO,
                        'wp',
                        InputOption::VALUE_OPTIONAL,
                        'Setting it to true will generate POPO files',
                        true
                    ),
                    new InputOption(
                        static::OPTION_WITH_INTERFACE,
                        'wi',
                        InputOption::VALUE_OPTIONAL,
                        'Setting it to true will generate interfaces for POPO files',
                        null
                    ),
                ]
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->buildConfigurator($input, $output);
        $items = $config->getSelectedItems();

        foreach ($items as $name => $configItem) {
            $configItem->getConfigurator()->setModelHelperConfigurator($this->modelHelperConfigurator);
            $this->configurationTable->showConfigurationTable($configItem->getConfigurator());
            $this->facade->generate($configItem->getConfigurator());
        }

        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return \Popo\Configurator\ConfigContainer
     * @throws \LogicException
     */
    protected function buildConfigurator(InputInterface $input, OutputInterface $output): ConfigContainer
    {
        $config = $this->buildConfig($input);
        $configSections = $input->getArgument(static::ARGUMENT_CONFIG_SECTION_NAME);

        if (empty($configSections)) {
            $configSections = array_keys($config->getConfigItems());
        }

        $configItems = [];
        foreach ($configSections as $name) {
            $item = $config->getConfigByName($name);

            $configurator = (new Configurator())
                ->setConfigName($name)
                ->setModelHelperConfigurator(new ModelHelperConfigurator())
                ->setSchemaConfigurator(new SchemaConfigurator())
                ->setSchemaDirectory($item->getSchema())
                ->setTemplateDirectory($item->getTemplate())
                ->setOutputDirectory($item->getOutput())
                ->setNamespace($item->getNamespace())
                ->setNamespaceWithInterface($item->getNamespaceWithInterface())
                ->setExtension($item->getExtension())
                ->setIsAbstract($item->isAbstract())
                ->setExtends($item->getExtends())
                ->setReturnType($item->getReturnType())
                ->setWithPopo($item->isWithPopo())
                ->setWithInterface($item->isWithInterface());

            $item->setConfigurator($configurator);

            $configItems[$name] = $item;
        }

        $config->setSelectedItems($configItems);

        return $config;
    }

    protected function buildConfig(InputInterface $input): ConfigContainer
    {
        $config = new ConfigContainer($input->getOption(static::OPTION_CONFIG_FILENAME));

        $arguments = [
            static::OPTION_SCHEMA => $input->getOption(static::OPTION_SCHEMA),
            static::OPTION_TEMPLATE => $input->getOption(static::OPTION_TEMPLATE),
            static::OPTION_OUTPUT => $input->getOption(static::OPTION_OUTPUT),
            static::OPTION_NAMESPACE => $input->getOption(static::OPTION_NAMESPACE),
            static::OPTION_EXTENSION => $input->getOption(static::OPTION_EXTENSION),
            static::OPTION_IS_ABSTRACT => $input->getOption(static::OPTION_IS_ABSTRACT),
            static::OPTION_EXTENDS => $input->getOption(static::OPTION_EXTENDS),
            static::OPTION_RETURN_TYPE => $input->getOption(static::OPTION_RETURN_TYPE),
            static::OPTION_WITH_POPO => $input->getOption(static::OPTION_WITH_POPO),
            static::OPTION_WITH_INTERFACE => $input->getOption(static::OPTION_WITH_INTERFACE),
        ];

        $config->setArguments($arguments);

        return $config;
    }
}
