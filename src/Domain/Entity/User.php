<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, EquatableInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $updatedAt;


    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
        $this->updatedAt = time();
    }

    public function registration(
        array $roles,
        string $username,
        string $password,
        string $email
    ) {
        $this->roles = $roles;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function update(
        string $password,
        string $email
    ) {
        $this->updatedAt = time();
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->password = null;
        $this->token = null;
    }

    public function isEqualTo(UserInterface $user)
    {
        return $this->id === $user->getId();
    }
}
