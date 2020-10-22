<?php

declare(strict_types = 1);

namespace Popo\Command;

use Popo\Command\Config\Config;
use Popo\Command\Config\Item;
use Popo\Configurator;
use Popo\Model\Helper\ConfigurationTable;
use Popo\Model\Helper\ModelHelperConfigurator;
use Popo\PopoFacade;
use Popo\PopoFacadeInterfaces;
use Popo\Schema\SchemaConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function getcwd;
use function is_file;
use function parse_ini_file;
use function rtrim;
use const DIRECTORY_SEPARATOR;

abstract class AbstractCommand extends Command
{
    const COMMAND_NAME = 'unknown';
    const COMMAND_DESCRIPTION = 'unknown';
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
    const OPTION_SHOW_CONFIGURATION = 'showConfiguration';
    const OPTION_SHOW_CONFIGURATION_BORDER = 'showConfigurationBorder';
    const OPTION_SHOW_PROGRESS_BAR = 'showProgressBar';

    protected ?PopoFacadeInterfaces $facade;

    protected ?ConfigurationTable $configurationTable;

    protected Configurator $configurator;

    protected ModelHelperConfigurator$modelHelperConfigurator;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): int;

    public function setFacade(PopoFacadeInterfaces $facade): void
    {
        $this->facade = $facade;
    }

    protected function getFacade(): PopoFacadeInterfaces
    {
        if (empty($this->facade)) {
            $this->facade = new PopoFacade();
        }

        return $this->facade;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->modelHelperConfigurator = (new ModelHelperConfigurator())
            ->setShowConfiguration($input->getOption(static::OPTION_SHOW_CONFIGURATION))
            ->setShowProgressBar($input->getOption(static::OPTION_SHOW_PROGRESS_BAR))
            ->setShowBorder($input->getOption(static::OPTION_SHOW_CONFIGURATION_BORDER));

        $this->configurationTable = new ConfigurationTable($output);
    }

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition([
                new InputArgument(static::ARGUMENT_CONFIG_SECTION_NAME, InputOption::VALUE_OPTIONAL, 'Config section name', []),
                new InputOption(static::OPTION_CONFIG_FILENAME, 'c', InputOption::VALUE_OPTIONAL, 'Config filename', '.popo'),
                new InputOption(static::OPTION_SCHEMA, 's', InputOption::VALUE_OPTIONAL, 'Schema directory', 'popo/'),
                new InputOption(static::OPTION_TEMPLATE, 't', InputOption::VALUE_OPTIONAL, 'Template directory', 'vendor/popo/generator/templates/'),
                new InputOption(static::OPTION_OUTPUT, 'o', InputOption::VALUE_OPTIONAL, 'Directory for generated files', 'src/Configurator/'),
                new InputOption(static::OPTION_NAMESPACE, 'm', InputOption::VALUE_OPTIONAL, 'Namespace for generated files', 'Configurator'),
                new InputOption(static::OPTION_EXTENSION, 'x', InputOption::VALUE_OPTIONAL, 'Extension of generated files', '.php'),
                new InputOption(static::OPTION_IS_ABSTRACT, 'a', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate abstract classes', null),
                new InputOption(static::OPTION_EXTENDS, 'e', InputOption::VALUE_OPTIONAL, 'Which class should the generated classes inherit from', null),
                new InputOption(static::OPTION_RETURN_TYPE, 'r', InputOption::VALUE_OPTIONAL, 'What fromArray(..) method should return', null),
                new InputOption(static::OPTION_WITH_POPO, 'wp', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate POPO files', true),
                new InputOption(static::OPTION_WITH_INTERFACE, 'wi', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate interfaces', null),
                new InputOption(static::OPTION_SHOW_CONFIGURATION, 'sc', InputOption::VALUE_OPTIONAL, 'Show configuration table with settings defined in config file', true),
                new InputOption(static::OPTION_SHOW_CONFIGURATION_BORDER, 'scb', InputOption::VALUE_OPTIONAL, 'Show border when showing configuration table', true),
                new InputOption(static::OPTION_SHOW_PROGRESS_BAR, 'sp', InputOption::VALUE_OPTIONAL, 'Show progress bar', true),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->modelHelperConfigurator->isShowConfiguration() || $this->modelHelperConfigurator->isShowProgressBar()) {
            $output->writeln('');
            $output->writeln(sprintf('<fg=yellow>POPO</> <fg=green>v%s</>', PopoFacadeInterfaces::VERSION));
            $output->writeln('');
        }

        return $this->executeCommand($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return Config
     */
    protected function buildConfigurator(InputInterface $input, OutputInterface $output): Config
    {
        $config = $this->buildConfig($input);
        $configSections = $input->getArgument(static::ARGUMENT_CONFIG_SECTION_NAME);

        if (empty($configSections)) {
            $configSections = array_keys($config->getConfigItems());
        }

        $configItems = [];
        foreach ($configSections as $name) {
            $item = $config->getConfigByName($name);

            if (!($item instanceof Item)) {
                throw new \LogicException(sprintf('Unknown config section: "%s". Available sections: %s',
                    $name,
                    implode(', ', array_keys($config->getData()))
                ));
            }

            $configurator = (new Configurator())
                ->setConfigName($name)
                ->setOutput($output)
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

        $config->setItemsToGenerate($configItems);

        return $config;
    }

    protected function buildConfig(InputInterface $input): Config
    {
        $config = $this->loadConfig($input->getOption(static::OPTION_CONFIG_FILENAME));

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

    protected function loadConfig(?string $configFilename = null): Config
    {
        $filename = $this->getPopoFilename($configFilename);

        if (!is_file($filename)) {
            throw new \LogicException(sprintf(
                'Config file: "%s" not found',
                $filename
            ));
        }

        $data = parse_ini_file($filename, true) ?? [];
        $config = (new Config)->setData($data);

        return $config;
    }

    protected function getPopoFilename(?string $configFilename = null): string
    {
        return rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $configFilename;
    }
}
