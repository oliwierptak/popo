<?php declare(strict_types = 1);

namespace Popo\Writer;

use Popo\Schema\Reader\Schema;

class SchemaWriter
{
    protected FileWriter $popoWriter;

    protected FileWriter $dtoWriter;

    protected FileWriter $abstractWriter;

    public function __construct(
        FileWriter $popoWriter,
        FileWriter $dtoWriter,
        FileWriter $abstractWriter
    ) {
        $this->popoWriter = $popoWriter;
        $this->dtoWriter = $dtoWriter;
        $this->abstractWriter = $abstractWriter;
    }

    public function writePopo(string $filename, Schema $schema): void
    {
        $this->popoWriter->write($filename, $schema);
    }

    public function writeDto(string $filename, Schema $schema): void
    {
        $this->dtoWriter->write($filename, $schema);
    }

    public function writeAbstract(string $filename, Schema $schema): void
    {
        $this->abstractWriter->write($filename, $schema);
    }
}
