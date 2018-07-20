<?php

declare(strict_types = 1);

namespace Generated\Popo;

interface BuzzInterface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \Generated\Popo\BuzzInterface
    */
    public function fromArray(array $data): \Generated\Popo\BuzzInterface;

    
    /**
     * @return string|null
     */
    public function getBuzz(): ?string;

    /**
     * @param string|null $buzz
     *
     * @return self
     */
    public function setBuzz(?string $buzz): \Generated\Popo\BuzzInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireBuzz(): string;

}
