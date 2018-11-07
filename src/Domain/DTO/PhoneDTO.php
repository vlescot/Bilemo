<?php
declare(strict_types=1);

namespace App\Domain\DTO;

final class PhoneDTO
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $price;

    /**
     * @var int
     */
    public $stock;

    /**
     * PhoneDTO constructor.
     *
     * @param string $description
     * @param int $price
     * @param int $stock
     */
    public function __construct(
        string $description,
        int $price,
        int $stock
    ) {
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
    }
}
