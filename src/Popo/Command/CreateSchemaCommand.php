<?php declare(strict_types = 1);

namespace Popo\Command;

use Popo\PopoConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use function file_exists;
use function fputs;

/**
 * @method \Symfony\Component\Console\Application getApplication()
 */
class CreateSchemaCommand extends AbstractCommand
{
    public const COMMAND_NAME = 'create-schema';

    public const COMMAND_DESCRIPTION = 'Generate POPO schema file';

    public const OPTION_SCHEMA_PATH = 'schemaPath';

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
                        'Path where generated schema file will be saved',
                        null
                    ),
                ]
            );
    }

    /**
     * @throws \Exception
     */
    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $configurator = new PopoConfigurator();
        $output->writeln('');
        $helper = $this->getHelper('question');

        $question = new Question('<fg=green>Schema filename</> <fg=gray>[popo.yml]</>: ', 'popo.yml');
        $question->setAutocompleterValues(['popo.yml']);
        $configurator->setSchemaPath($helper->ask($input, $output, $question));

        $question = new Question('<fg=green>namespace</> <fg=gray>[Popo]</>: ', 'Popo');
        $question->setAutocompleterValues(['Popo']);
        $namespace = $helper->ask($input, $output, $question);
        $configurator->setNamespace($namespace);

        $question = new Question('<fg=green>outputPath</> <fg=gray>[src/]</>: ', 'src/');
        $question->setAutocompleterValues(['src/']);
        $outputPath = $helper->ask($input, $output, $question);
        $configurator->setOutputPath($outputPath);

        $question = new Question('<fg=green>Schema Name</> <fg=gray>[Example]</>: ', 'Example');
        $question->setAutocompleterValues(['Example']);
        $schemaName = $helper->ask($input, $output, $question);

        $generatedSchema = sprintf(
            '$:
  config:
    namespace: %s
    outputPath: %s

%s:
  Foo:
    property: [
      {name: id, type: int}
      {name: title, default: Lorem Ipsum}
      {name: bar, type: popo, default: Bar::class}
      {name: modified, type: datetime, default: "2022-01-01 15:22:17", extra: {timezone: "Europe/Paris", format: "D, d M y H:i:s O"}}
    ]}}
    
  Bar:
    property: [
      {name: id, type: int}
      {name: items, type: array, itemName: BarItem, itemType: BarItem::class}
    ]}}  
      
  BarItem:
    property: [
      {name: id, type: int}
      {name: title, default: A bar item}
    ]}}
',
            $configurator->getNamespace(),
            $configurator->getOutputPath(),
            $schemaName,
        );

        $output->writeln('');
        $output->writeln($generatedSchema);

        $question = new ConfirmationQuestion(
            '<fg=green>Save schema under: \'' . $configurator->getSchemaPath() . '\'?</> <fg=gray>[y/n]</>', true
        );
        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        if (file_exists($configurator->getSchemaPath())) {
            $question = new ConfirmationQuestion(
                '<fg=yellow>Schema file under: \'' . $configurator->getSchemaPath(
                ) . '\' already exists. Overwrite?</> <fg=gray>[</><fg=white>y</><fg=gray>/n]</>', true
            );
            if (!$helper->ask($input, $output, $question)) {
                return Command::SUCCESS;
            }
        }

        $this->saveSchemaFile($configurator, $generatedSchema);

        $output->writeln('');
        $output->writeln('<fg=green>Saved schema file under</>: ' . $configurator->getSchemaPath());

        $output->writeln('');
        $output->writeln('<fg=green>Run</>: vendor/bin/popo generate -s ' . $configurator->getSchemaPath());

        $configurator->setSchemaFilenameMask('*.yml');

        return $this->runCommand($configurator, $output);
    }

    protected function saveSchemaFile(PopoConfigurator $configurator, string $generatedSchema): void
    {
        $handle = null;
        try {
            $handle = fopen($configurator->getSchemaPath(), 'w');
            /** @phpstan-ignore-next-line */
            fputs($handle, $generatedSchema);
        }
        finally {
            /** @phpstan-ignore-next-line */
            fclose($handle);
        }
    }

    /**
     * @param \Popo\PopoConfigurator $configurator
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     * @throws \Exception
     */
    protected function runCommand(PopoConfigurator $configurator, OutputInterface $output): int
    {
        $command = $this->getApplication()->find(GenerateCommand::COMMAND_NAME);

        $arguments = [
            '-s' => $configurator->getSchemaPath(),
            '-o' => $configurator->getOutputPath(),
        ];

        $parameters = new ArrayInput($arguments);

        return $command->run($parameters, $output);
    }
}
