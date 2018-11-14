<?php
declare(strict_types=1);

namespace App\App\Pagination;

use App\App\Pagination\Interfaces\PaginatorInterface;
use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;

/**
 * Class Paginator
 * @package App\App\Pagination
 */
final class Paginator implements PaginatorInterface
{
    /**
     * @var RepositoryAllowPaginationInterface
     */
    private $repository;

    /**
     * @var int
     */
    private $maxPerPage;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $nbResults;

    /**
     * @var array
     */
    private $currentPageResults;


    /**
     * {@inheritdoc}
     */
    public function __construct(
        RepositoryAllowPaginationInterface $repository,
        int $currentPage,
        int $maxPerPage,
        array $filters
    ) {
        $this->maxPerPage = $maxPerPage;
        $this->currentPage = $currentPage;
        $this->repository = $repository;

        $this->nbResults = $repository->countAll($filters);

        $first = $currentPage * $maxPerPage - $maxPerPage;
        $this->currentPageResults = $this->repository->getSliceAndFilteredResults($first, $this->maxPerPage, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPageResults(): array
    {
        return $this->currentPageResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults(): int
    {
        return $this->nbResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbPages(): int
    {
        return (int) ceil($this->nbResults / $this->maxPerPage);
    }

    /**
     * {@inheritdoc}
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage * $this->maxPerPage < $this->nbResults;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPage(): int
    {
        return $this->currentPage + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousPage(): int
    {
        return $this->currentPage -1;
    }
}
