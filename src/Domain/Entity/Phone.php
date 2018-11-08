<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Phone
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
    private $brand;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $price;

    /**
     * @var int
     */
    private $stock;

    /**
     * Phone constructor.
     *
     * @param string $brand
     * @param string $model
     * @param string $description
     * @param int $price
     * @param int $stock
     */
    public function __construct(
        string $brand,
        string $model,
        string $description,
        int $price,
        int $stock
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
        $this->updatedAt = time();
        $this->brand = $brand;
        $this->model = $model;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
    }

    /**
     * @param string|null $description
     * @param int|null $price
     * @param int|null $stock
     */
    public function update(
        string $description = null,
        int $price = null,
        int $stock = null
    ) {
        $this->updatedAt = time();
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
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
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }
}
