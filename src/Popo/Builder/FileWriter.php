<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use Popo\Schema\Schema;
use RuntimeException;

class FileWriter
{
    public function save(PhpFile $file, Schema $schema): string
    {
        $handle = null;
        try {
            $filename = $this->generateFilename($schema);

            $directory = pathinfo($filename, PATHINFO_DIRNAME);
            if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new RuntimeException(sprintf('Popo output directory "%s" could not be created', $directory));
            }

            $handle = fopen($filename, 'wb');
            if ($handle === false) {
                throw new RuntimeException('Could not open file: "' . $filename . '" for writing');
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
