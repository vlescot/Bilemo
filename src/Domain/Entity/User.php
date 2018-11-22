<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;

/**
 * Class User
 * @package App\Domain\Entity
 */
class User extends ApiUser
{
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
        $this->roles = ['ROLE_USER'];
        $this->createdAt = time();
        $this->updatedAt = time();
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $phoneNumber
     * @param Client $client
     */
    public function create(
        string $username,
        string $password,
        string $email,
        string $phoneNumber,
        Client $client
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->client = $client;
    }
}
