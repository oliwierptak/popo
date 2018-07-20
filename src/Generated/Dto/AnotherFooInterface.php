<?php

declare(strict_types = 1);

namespace Generated\Popo;

interface AnotherFooInterface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \Generated\Popo\AnotherFooInterface
    */
    public function fromArray(array $data): \Generated\Popo\AnotherFooInterface;

    
    /**
     * @return integer|null
     */
    public function getId(): ?int;

    /**
     * @param integer|null $id
     *
     * @return self
     */
    public function setId(?int $id): \Generated\Popo\AnotherFooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return integer
     */
    public function requireId(): int;

    /**
     * @return string|null
     */
    public function getAnotherFoo(): ?string;

    /**
     * @param string|null $anotherFoo
     *
     * @return self
     */
    public function setAnotherFoo(?string $anotherFoo): \Generated\Popo\AnotherFooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireAnotherFoo(): string;

}
