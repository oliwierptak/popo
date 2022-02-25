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
        if ($schema->getConfig()->getComment() !== null) {
            $file->addComment($schema->getConfig()->getComment());
        }

        return $file;
    }
}
