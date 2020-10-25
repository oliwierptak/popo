<?php declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Generator\GeneratorInterface;
use Popo\Schema\Reader\Schema;
use Popo\Writer\Exception\WriterException;
use Throwable;
use function fclose;
use function fopen;
use function fwrite;
use function mb_strlen;

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
     */
    protected function writeFile(string $filename, string $content): void
    {
        try {
            $file = fopen($filename, 'w');
            fwrite($file, $content, mb_strlen($content));
            fclose($file);
        }
        catch (Throwable $exception) {
            throw new WriterException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
