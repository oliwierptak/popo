<?php

declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Popo\Schema\Reader\Schema;
use Popo\Writer\Exception\WriterException;

class FileWriter
{
    protected GeneratorInterface $generator;

    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function write(string $filename, Schema $schema): void
    {
        $content = $this->generator->generate($schema);

        $this->writeFile($filename, $content);
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @return void
     * @throws \Popo\Writer\Exception\WriterException
     *
     */
    protected function writeFile(string $filename, string $content): void
    {
        try {
            $f = \fopen($filename, 'w');
            \fwrite($f, $content, \mb_strlen($content));
            \fclose($f);
        } catch (\Throwable $exception) {
            throw new WriterException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }
}
