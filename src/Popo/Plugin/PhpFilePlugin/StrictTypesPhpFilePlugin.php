<?php

declare(strict_types = 1);

namespace Popo\Plugin\PhpFilePlugin;

use Nette\PhpGenerator\PhpFile;
use Popo\Plugin\PhpFilePluginInterface;
use Popo\Schema\Schema;

class StrictTypesPhpFilePlugin implements PhpFilePluginInterface
{
    public function run(PhpFile $file, Schema $schema): PhpFile
    {
        $file->setStrictTypes(true);

        return $file;
    }
}
