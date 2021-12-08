<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use Popo\Schema\Schema;

class FileWriter
{
    /**
     * @throws \Throwable
     */
    public function save(PhpFile $file, Schema $schema): string
    {
        $handle = null;
        try {
            $filename = $this->generateFilename($schema);

            @mkdir(pathinfo($filename, PATHINFO_DIRNAME), 0775, true);

            $handle = fopen($filename, 'w');
            if ($handle === false) {
                throw new \RuntimeException('Could not open file: "' . $filename . '" for writing');
            }
            fwrite($handle, $this->print($file));
        }
        finally {
            if ($handle) {
                fclose($handle);
            }
        }

        return $filename;
    }

    public function generateFilename(Schema $schema): string
    {
        $path = rtrim($schema->getConfig()->getOutputPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $namespace = $schema->getConfig()->getNamespace();
        $namespaceRoot = trim((string) $schema->getConfig()->getNamespaceRoot());

        if ($namespaceRoot !== '') {
            $namespace = str_replace($namespaceRoot, '', $namespace);
        }
        $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

        return sprintf(
            '%s/%s/%s.php',
            rtrim($path, DIRECTORY_SEPARATOR),
            $namespace,
            $schema->getName()
        );
    }

    protected function print(PhpFile $file): string
    {
        return (new PsrPrinter)->printFile($file);
    }
}
