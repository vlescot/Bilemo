<?php
declare(strict_types=1);

namespace App\App\Pagination\Interfaces;

/**
 * Interface PaginatedCollectionInterface
 * @package App\App\Pagination\Interfaces
 */
interface PaginatedCollectionInterface
{
    /**
     * PaginatedCollectionInterface constructor.
     *
     * @param array $phones
     * @param int $total
     */
    public function __construct(array $phones, int $total);

    /**
     * @param $ref
     * @param $url
     *
     * @return mixed
     */
    public function addLink($ref, $url);

    /**
     * @return int
     */
    public function getTotal(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return array
     */
    public function getLinks(): array;

    /**
     * @return array
     */
    public function getPhones(): array;
}
