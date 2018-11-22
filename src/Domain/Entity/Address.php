<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Address
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $streetAddress;

    /**
     * @var string
     */
    private $city;

    /**
     * @var int
     */
    private $postcode;

    /**
     * Address constructor.
     *
     * @param string $streetAddress
     * @param string $city
     * @param int $postcode
     * @throws \Exception
     */
    public function __construct(
        string $streetAddress,
        string $city,
        int $postcode
    ) {
        $this->id = Uuid::uuid4();
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postcode = $postcode;
    }

    /**
     * @param string $streetAddress
     * @param string $city
     * @param int $postcode
     */
    public function update(
        string $streetAddress,
        string $city,
        int $postcode
    ) {
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postcode = $postcode;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return int
     */
    public function getPostcode(): int
    {
        return $this->postcode;
    }
}
