<?php

declare(strict_types = 1);

namespace Popo\Command;

use Popo\Builder\BuilderConfigurator;
use Popo\PopoFacade;
use Popo\PopoFacadeInterfaces;
use Popo\Schema\SchemaConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function array_merge;
use function getcwd;
use function is_file;
use function parse_ini_file;
use function rtrim;
use const DIRECTORY_SEPARATOR;

abstract class AbstractCommand extends Command
{
    const COMMAND_NAME = 'unknown';
    const COMMAND_DESCRIPTION = 'unknown';
    const OPTION_SCHEMA = 'schema';
    const OPTION_TEMPLATE = 'template';
    const OPTION_OUTPUT = 'output';
    const OPTION_NAMESPACE = 'namespace';
    const OPTION_EXTENSION = 'extension';
    const OPTION_IS_ABSTRACT = 'abstract';
    const OPTION_EXTENDS = 'extends';
    const OPTION_RETURN_TYPE = 'returnType';
    const OPTION_WITH_INTERFACE = 'withInterface';

    /**
     * @var \Popo\PopoFacadeInterfaces
     */
    protected $facade;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): ?int;

    public function setFacade(PopoFacadeInterfaces $facade): void
    {
        $this->facade = $facade;
    }

    protected function getFacade(): PopoFacadeInterfaces
    {
        if ($this->facade === null) {
            $this->facade = new PopoFacade();
        }

        return $this->facade;
    }

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition([
                new InputOption(static::OPTION_SCHEMA, 's', InputOption::VALUE_OPTIONAL, 'Schema directory', 'popo/'),
                new InputOption(static::OPTION_TEMPLATE, 't', InputOption::VALUE_OPTIONAL, 'Template directory', 'vendor/popo/generator/templates/'),
                new InputOption(static::OPTION_OUTPUT, 'o', InputOption::VALUE_OPTIONAL, 'Directory for generated files', 'src/Popo/'),
                new InputOption(static::OPTION_NAMESPACE, 'm', InputOption::VALUE_OPTIONAL, 'Namespace for generated files', 'Popo'),
                new InputOption(static::OPTION_EXTENSION, 'x', InputOption::VALUE_OPTIONAL, 'Extension of generated files', '.php'),
                new InputOption(static::OPTION_IS_ABSTRACT, 'a', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate abstract classes', null),
                new InputOption(static::OPTION_EXTENDS, 'e', InputOption::VALUE_OPTIONAL, 'Which class should the generated classes inherit from', null),
                new InputOption(static::OPTION_RETURN_TYPE, 'r', InputOption::VALUE_OPTIONAL, 'What fromArray(..) method should return', 'self'),
                new InputOption(static::OPTION_WITH_INTERFACE, 'i', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate interfaces', false),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $configurator = $this->buildConfigurator($input);

        $output->writeln('<fg=green>POPO configuration</>');

        $table = new Table($output);
        $table->setStyle('compact');

        $table
            ->setRows([
                ['schema', $configurator->getSchemaDirectory()],
                new TableSeparator(),
                ['template', $configurator->getTemplateDirectory()],
                new TableSeparator(),
                ['output', $configurator->getOutputDirectory()],
                new TableSeparator(),
                ['namespace', $configurator->getNamespace()],
                new TableSeparator(),
                ['extension', $configurator->getExtension()],
                new TableSeparator(),
                ['abstract', (int)$configurator->getIsAbstract()],
                new TableSeparator(),
                ['extends', $configurator->getExtends()],
                new TableSeparator(),
                ['returnType', $configurator->getReturnType()],
                new TableSeparator(),
                ['withInterface', (int)$configurator->getWithInterface()],
            ]);
        $table->render();

        return $this->executeCommand($input, $output);
    }

    protected function buildConfigurator(InputInterface $input): BuilderConfigurator
    {
        $arguments = $this->getDotData($input);

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($arguments['schema'])
            ->setTemplateDirectory($arguments['template'])
            ->setOutputDirectory($arguments['output'])
            ->setNamespace($arguments['namespace'])
            ->setExtension($arguments['extension'])
            ->setIsAbstract(((bool)$arguments['abstract']) ?? null)
            ->setExtends($arguments['extends'])
            ->setReturnType($arguments['returnType'])
            ->setWithInterface($arguments['withInterface']);

        return $configurator;
    }

    protected function getDotData(InputInterface $input): array
    {
        $config = $this->getDotConfig();

        $arguments = [
            static::OPTION_SCHEMA => $input->getOption(static::OPTION_SCHEMA),
            static::OPTION_TEMPLATE => $input->getOption(static::OPTION_TEMPLATE),
            static::OPTION_OUTPUT => $input->getOption(static::OPTION_OUTPUT),
            static::OPTION_NAMESPACE => $input->getOption(static::OPTION_NAMESPACE),
            static::OPTION_EXTENSION => $input->getOption(static::OPTION_EXTENSION),
            static::OPTION_IS_ABSTRACT => $input->getOption(static::OPTION_IS_ABSTRACT),
            static::OPTION_EXTENDS => $input->getOption(static::OPTION_EXTENDS),
            static::OPTION_RETURN_TYPE => $input->getOption(static::OPTION_RETURN_TYPE),
            static::OPTION_WITH_INTERFACE => $input->getOption(static::OPTION_WITH_INTERFACE),
        ];

        $result = array_merge($arguments, $config);

        return $result;
    }

    protected function getDotConfig(): array
    {
        $config = [];
        $default = [
            GeneratePopoCommand::COMMAND_NAME => [],
            GenerateDtoCommand::COMMAND_NAME => [],
        ];

        $configFile = $this->getPopoFilename();
        if (is_file($configFile)) {
            $config = parse_ini_file($configFile, true) ?? $default;
        }

        return $config[static::COMMAND_NAME];
    }

    protected function getPopoFilename(): string
    {
        return rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.popo';
    }
}
