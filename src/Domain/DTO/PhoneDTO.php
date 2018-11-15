<?php
declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\Entity\Manufacturer;

/**
 * Class PhoneDTO
 * @package App\Domain\DTO
 */
final class PhoneDTO
{
    /**
     * @var Manufacturer
     */
    public $manufacturer;

    /**
     * @var string
     */
    public $model;

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
     * @param Manufacturer|null $manufacturer
     * @param string|null $model
     */
    public function __construct(
        string $description,
        int $price,
        int $stock,
        Manufacturer $manufacturer = null,
        string $model = null
    ) {
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
    }
}
