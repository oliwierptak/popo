<?php declare(strict_types = 1);

namespace Popo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigureCommand extends Command
{
    const COMMAND_NAME = 'configure';
    const COMMAND_DESCRIPTION = 'Configure settings and save them to .popo file';
    const OPTION_CONFIG_FILE = 'config-file';

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition(
                [
                    new InputOption(
                        static::OPTION_CONFIG_FILE,
                        's',
                        InputOption::VALUE_OPTIONAL,
                        'Configuration filename',
                        '.popo'
                    ),
                ]
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Configuration filename [.popo]: ', '.popo');
        $question->setAutocompleterValues(['.popo', '.popo.dist']);
        $configFilename = $helper->ask($input, $output, $question);

        $question = new Question('Schema name: ', '');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Schema [popo/]: ', 'popo/');
        $question->setAutocompleterValues(['popo/']);
        $schema = rtrim($helper->ask($input, $output, $question), '/').'/';

        $question = new Question('Template [vendor/popo/generator/templates/]: ', 'vendor/popo/generator/templates/');
        $question->setAutocompleterValues(['vendor/popo/generator/templates/']);
        $template = rtrim($helper->ask($input, $output, $question), '/').'/';

        $question = new Question('Output directory [src/Configurator/]: ', 'src/Configurator/');
        $question->setAutocompleterValues(['src/Configurator/', 'src/Popo/', 'src/Generated/']);
        $outputDirectory = rtrim($helper->ask($input, $output, $question), '/').'/';

        $question = new Question('Namespace [\App\Configurator]: ', 'App\Configurator');
        $question->setAutocompleterValues(['\App\Configurator']);
        $namespace = rtrim($helper->ask($input, $output, $question), '\\');;

        $question = new Question('Extension [.php]: ', '.php');
        $question->setAutocompleterValues(['.php']);
        $extension = $helper->ask($input, $output, $question);

        $question = new Question('Abstract []: ', null);
        $question->setAutocompleterValues([null]);
        $abstract = $helper->ask($input, $output, $question);

        $question = new Question('Extends []: ', '');
        $question->setAutocompleterValues(['']);
        $extends = $helper->ask($input, $output, $question);

        $content = <<<EOT
[${name}]
schema = ${schema}
template = ${template}
output = ${outputDirectory}
namespace = $namespace
extension = $extension
abstract = $abstract
extends = $extends
withInterface = 0
withPopo = 1
returnType =
EOT;

        $output->writeln('Generating configuration file under: ' . $configFilename);

        \file_put_contents($configFilename, $content);

        return 0;
    }
}
