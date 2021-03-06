<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Client
 * @package App\Domain\Entity
 */
class Client implements UserInterface
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $updatedAt;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var \ArrayAccess
     */
    private $users;


    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->roles = ['ROLE_CLIENT'];
        $this->createdAt = time();
        $this->updatedAt = time();
        $this->users = new ArrayCollection();
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $phoneNumber
     */
    public function create(
        string $username,
        string $password,
        string $email,
        string $phoneNumber
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }


    /**
     * @param string $password
     * @param string $email
     * @param string $phoneNumber
     */
    public function update(
        string $password = null,
        string $email = null,
        string $phoneNumber = null
    ) {
        $this->updatedAt = time();
        $this->password = $password;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
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
    public function getPassword(): ? string
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
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return \ArrayAccess
     */
    public function getUsers(): \ArrayAccess
    {
        return $this->users;
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
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        return $this->id === $user->getId();
    }
}
