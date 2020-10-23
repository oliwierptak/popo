<?php

declare(strict_types = 1);

namespace Popo\Model\Helper;

use Popo\Configurator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressIndicator
{
    protected OutputInterface $output;

    protected Configurator $configurator;

    protected ?ProgressBar $progressBar;

    protected int $max = 0;

    public function __construct(OutputInterface $output, Configurator $configurator, int $max = 0)
    {
        $this->output = $output;
        $this->configurator = $configurator;
        $this->max = $max;
    }

    public function start(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $borderChar = '|';
        if (!$this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            //$this->progressBar->setFormat("| %current%/%max% %bar% %percent:3s%% |\n| %remaining:-10s% %memory:53s |%");
            $borderChar = '';
        }

        $this->progressBar = new ProgressBar($this->output->section(), $this->max);
        $this->progressBar->setFormat("${borderChar} %current%/%max% %bar% %percent:3s%% ${borderChar}\n${borderChar} %remaining:-10s% %memory:53s ${borderChar}%");

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

    public function stop(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            $this->output->writeln(sprintf(
                '>> Generated %d POPO files for "%s" section',
                $this->max,
                $this->configurator->getConfigName()
            ));
            return;
        }

        $format = " %elapsed% %memory:56s%";
        if ($this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            $format = "| %elapsed% %memory:56s |%";
        }

        $this->progressBar->setFormat($format);
        $this->progressBar->finish();

        $this->showProgressBarSeparator();
    }

    protected function showProgressBarSeparator(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $separator = '';
        if ($this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            $separator = '+---------------+--------------------------------------------------+';
        }

        $this->output->writeln($separator);
    }

}
