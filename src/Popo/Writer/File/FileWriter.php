<?php

declare(strict_types = 1);

namespace Popo\Writer\File;

use Popo\Generator\GeneratorInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Writer\Exception\WriterException;

class FileWriter implements FileWriterInterface
{
    /**
     * @var \Popo\Generator\GeneratorInterface
     */
    protected $generator;

    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function write(string $filename, SchemaInterface $schema): void
    {
        $content = $this->generator->generate($schema);

        $this->writeFile($filename, $content);
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @throws \Popo\Writer\Exception\WriterException
     *
     * @return void
     */
    protected function writeFile(string $filename, string $content): void
    {
        try {
            $f = \fopen($filename, 'w');
            \fwrite($f, $content, \mb_strlen($content));
            \fclose($f);
        } catch (\Throwable $e) {
            throw new WriterException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
