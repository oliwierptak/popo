<?php declare(strict_types = 1);

namespace Popo\Model\Helper;

class ModelHelperConfigurator
{
    protected bool $showConfiguration = false;
    protected bool $showBorder = true;
    protected bool $showProgressBar = false;
    protected bool $showSummary = true;

    public function isShowConfiguration(): bool
    {
        return $this->showConfiguration;
    }

    public function setShowConfiguration(bool $showConfiguration): self
    {
        $this->showConfiguration = $showConfiguration;

        return $this;
    }

    public function isShowBorder(): bool
    {
        return $this->showBorder;
    }

    public function setShowBorder(bool $showBorder): self
    {
        $this->showBorder = $showBorder;

        return $this;
    }

    public function isShowProgressBar(): bool
    {
        return $this->showProgressBar;
    }

    public function setShowProgressBar(bool $showProgressBar): self
    {
        $this->showProgressBar = $showProgressBar;

        return $this;
    }

    public function isShowSummary(): bool
    {
        return $this->showSummary;
    }

    public function setShowSummary(bool $showSummary): self
    {
        $this->showSummary = $showSummary;

        return $this;
    }
}
