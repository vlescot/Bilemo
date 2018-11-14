<?php
declare(strict_types=1);

namespace App\Domain\DTO;

/**
 * Class UserDTO
 * @package App\Domain\DTO
 */
final class UserDTO
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $password;

    /**
     * UserDTO constructor.
     *
     * @param string $email
     * @param string $phoneNumber
     * @param string $password
     * @param string|null $username
     */
    public function __construct(
        string $email,
        string $phoneNumber,
        string $password,
        string $username = null
    ) {
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
        $this->username = $username;
    }
}
