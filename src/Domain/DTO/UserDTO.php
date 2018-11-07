<?php
declare(strict_types=1);

namespace App\Domain\DTO;

final class UserDTO
{
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
     * @param string $email
     * @param string $password
     */
    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
    }
}
