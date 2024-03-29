<?php

declare(strict_types = 1);

namespace Popo\Plugin\PhpFilePlugin;

use Nette\PhpGenerator\PhpFile;
use Popo\Schema\Schema;

class CommentPhpFilePlugin
{
    public function run(PhpFile $file, Schema $schema): PhpFile
    {
        if ($schema->getConfig()->getPhpComment() !== null) {
            $file->addComment($schema->getConfig()->getPhpComment());
        }

        return $file;
    }
}
