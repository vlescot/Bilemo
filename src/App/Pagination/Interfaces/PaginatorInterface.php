<?php
declare(strict_types=1);

namespace App\App\Pagination\Interfaces;

use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;

/**
 * Interface PaginatorInterface
 * @package App\App\Pagination\Interfaces
 */
interface PaginatorInterface
{
    /**
     * PaginatorInterface constructor.
     *
     * @param RepositoryAllowPaginationInterface $repository
     * @param int $currentPage
     * @param int $maxPerPage
     * @param array $filters
     */
    public function __construct(
        RepositoryAllowPaginationInterface $repository,
        int $currentPage,
        int $maxPerPage,
        array $filters
    );

    /**
     * @return array
     */
    public function getCurrentPageResults(): array;

    /**
     * @return int
     */
    public function getNbResults(): int;

    /**
     * @return int
     */
    public function getNbPages(): int;

    /**
     * @return bool
     */
    public function hasNextPage(): bool;

    /**
     * @return bool
     */
    public function hasPreviousPage(): bool;

    /**
     * @return int
     */
    public function getNextPage(): int;

    /**
     * @return int
     */
    public function getPreviousPage(): int;
}
