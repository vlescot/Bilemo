<?php
declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class UserDTO
 * @package App\Domain\DTO
 */
final class UserDTO
{
    /**
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var Client
     */
    public $client;


    /**
     * UserDTO constructor.
     *
     * @param string $phoneNumber
     * @param string $email
     * @param Address $address
     * @param Client|null $client
     * @param string|null $name
     */
    public function __construct(
        string $phoneNumber,
        string $email,
        Address $address,
        Client $client = null,
        string $name = null
    ) {
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->address = $address;
        $this->client = $client;
        $this->name = $name;
    }
}
