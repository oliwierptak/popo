<?php

declare(strict_types = 1);

namespace Generated\Popo;

interface FooInterface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \Generated\Popo\FooInterface
    */
    public function fromArray(array $data): \Generated\Popo\FooInterface;

    
    /**
     * @return integer|null
     */
    public function getFooId(): ?int;

    /**
     * @param integer|null $fooId
     *
     * @return self
     */
    public function setFooId(?int $fooId): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return integer
     */
    public function requireFooId(): int;

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @param string|null $username
     *
     * @return self
     */
    public function setUsername(?string $username): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireUsername(): string;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requirePassword(): string;

    /**
     * @return boolean|null
     */
    public function isLoggedIn(): ?bool;

    /**
     * @param boolean|null $isLoggedIn
     *
     * @return self
     */
    public function setIsLoggedIn(?bool $isLoggedIn): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return boolean
     */
    public function requireIsLoggedIn(): bool;

    /**
     * @return boolean|null
     */
    public function resetPassword(): ?bool;

    /**
     * @param boolean|null $resetPassword
     *
     * @return self
     */
    public function setResetPassword(?bool $resetPassword): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return boolean
     */
    public function requireResetPassword(): bool;

    /**
     * @return array|null
     */
    public function getOptionalData(): ?array;

    /**
     * @param array|null $optionalData
     *
     * @return self
     */
    public function setOptionalData(?array $optionalData): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function requireOptionalData(): array;

    /**
     * @return FooBar|null
     */
    public function getFooBar(): ?FooBar;

    /**
     * @param FooBar|null $fooBar
     *
     * @return self
     */
    public function setFooBar(?FooBar $fooBar): \Generated\Popo\FooInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return FooBar
     */
    public function requireFooBar(): FooBar;

}
