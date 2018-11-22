<?php
declare(strict_types=1);

namespace App\Domain\DTO;

/**
 * Class ClientDTO
 * @package App\Domain\DTO
 */
final class ClientDTO
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
     * ClientDTO constructor.
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
