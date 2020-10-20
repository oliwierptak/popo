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

    public function __construct(OutputInterface $output, Configurator $configurator)
    {
        $this->output = $output;
        $this->configurator = $configurator;
    }

    public function initProgressBar(int $max = 0): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $this->progressBar = new ProgressBar($this->configurator->getOutput()->section(), $max);

        $this->progressBar->setFormat(" %current%/%max% %bar% %percent:3s%% \n %remaining:-10s% %memory:53s %");
        if ($this->configurator->getModelHelperConfigurator()->isShowBorder()) {
            $this->progressBar->setFormat("| %current%/%max% %bar% %percent:3s%% |\n| %remaining:-10s% %memory:53s |%");
        }

        if (!$this->configurator->getModelHelperConfigurator()->isShowConfiguration()) {
            $this->output->writeln(sprintf(' Generating: <fg=yellow>%s</>' , $this->configurator->getConfigName()));
        }

        $this->progressBar->setBarWidth(55);
        $this->progressBar->start();
    }

    public function advanceProgressBar(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
            return;
        }

        $this->progressBar->advance();
    }

    public function finishProgressBar(): void
    {
        if (!$this->configurator->getModelHelperConfigurator()->isShowProgressBar()) {
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
