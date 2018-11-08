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
    public $password;

    /**
     * UserDTO constructor.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function __construct(
        string $email,
        string $password,
        string $username = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}
