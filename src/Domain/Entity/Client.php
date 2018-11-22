<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Client
 * @package App\Domain\Entity
 */
class Client extends ApiUser
{
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
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        return $this->id === $user->getId();
    }
}
