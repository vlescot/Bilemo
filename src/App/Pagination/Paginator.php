<?php
declare(strict_types=1);

namespace App\App\Pagination;

use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;

final class Paginator
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
     * Paginator constructor.
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
    ) {
        $this->maxPerPage = $maxPerPage;
        $this->currentPage = $currentPage;
        $this->repository = $repository;

        $this->nbResults = $repository->countAll($filters);

        $first = $currentPage * $maxPerPage - $maxPerPage;
        $this->currentPageResults = $this->repository->getSliceAndFilteredResults($first, $this->maxPerPage, $filters);
    }

    /**
     * @return array
     */
    public function getCurrentPageResults()
    {
        return $this->currentPageResults;
    }

    /**
     * @return int
     */
    public function getNbResults(): int // Total items
    {
        return $this->nbResults;
    }

    /**
     * @return int
     */
    public function getNbPages(): int
    {
        return (int) ceil($this->nbResults / $this->maxPerPage);
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->currentPage * $this->maxPerPage < $this->nbResults;
    }

    /**
     * @return bool
     */
    public function hasPreviousPage()
    {
        return $this->currentPage > 1;
    }

    /**
     * @return int
     */
    public function getNextPage(): int
    {
        return $this->currentPage + 1;
    }

    /**
     * @return int
     */
    public function getPreviousPage(): int
    {
        return $this->currentPage -1;
    }
}
