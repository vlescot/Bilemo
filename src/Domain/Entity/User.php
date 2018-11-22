<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package App\Domain\Entity
 */
class User
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var Client
     */
    private $client;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @param string $name
     * @param Address $address
     * @param string $email
     * @param string $phoneNumber
     * @param Client $client
     */
    public function create(
        string $name,
        string $phoneNumber,
        string $email,
        Address $address,
        Client $client
    ) {
        $this->createdAt = time();
        $this->updatedAt = time();
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->address = $address;
        $this->client = $client;
    }

    /**
     * @param Address $address
     * @param string $email
     * @param string $phoneNumber
     */
    public function update(
        string $phoneNumber,
        string $email,
        Address $address
    ) {
        $this->updatedAt =  time();
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->address = $address;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
