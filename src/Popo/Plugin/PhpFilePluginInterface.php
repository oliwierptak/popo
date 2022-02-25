<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\PhpFile;
use Popo\Schema\Schema;

interface PhpFilePluginInterface
{
    public function run(PhpFile $file, Schema $schema): PhpFile;
}
