<?php declare(strict_types = 1);

namespace Popo\Model\Helper;

use Popo\Configurator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressIndicator
{
    protected OutputInterface $output;

    protected Configurator $configurator;

    protected ?ProgressBar $progressBar;

    public function __construct(OutputInterface $output, Configurator $configurator)
    {
        $this->output = $output;
        $this->configurator = $configurator;
    }

    public function start(int $max = 0): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $borderChar = '|';
        if (!$this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            //$this->progressBar->setFormat("| %current%/%max% %bar% %percent:3s%% |\n| %remaining:-10s% %memory:53s |%");
            $borderChar = '';
        }

        $this->progressBar = new ProgressBar($this->output, $max);
        $this->progressBar->setFormat(
            "${borderChar} %current%/%max% %bar% %percent:3s%% ${borderChar}\n${borderChar} %remaining:-10s% %memory:53s ${borderChar}%"
        );

        if (!$this->configurator->getModelHelperConfigurator()->isShowConfiguration()) {
            $this->output->writeln(sprintf(' Generating: <fg=yellow>%s</>', $this->configurator->getConfigName()));
        }

        $this->progressBar->setBarWidth(55);
        $this->progressBar->start();
    }

    public function advance(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $this->progressBar->advance();
    }

    public function stop(int $max = 0): void
    {
        if ($this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            $format = " %elapsed% %memory:56s%";
            if ($this->configurator->getModelHelperConfigurator()->isShowBorder()) {
                $format = "| %elapsed% %memory:56s |%";
            }

            $this->progressBar->setFormat($format);
            $this->progressBar->finish();
            $this->showProgressBarSeparator();
        }

        if ($this->configurator->getModelHelperConfigurator()->isShowSummary()) {
            $this->output->writeln(
                sprintf(
                    ' <fg=green>✔</> Generated <fg=yellow>%d</> POPO files for "%s" section',
                    $max,
                    $this->configurator->getConfigName(),
                )
            );
        }
    }

    protected function showProgressBarSeparator(): void
    {
        $separator = '';
        if ($this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            $this->output->writeln('');
            $separator = '+---------------+--------------------------------------------------+';
        }

        $this->output->writeln($separator);
    }
}
