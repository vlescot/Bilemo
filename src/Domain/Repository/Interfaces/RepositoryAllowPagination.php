<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

interface RepositoryAllowPagination
{
    /**
     * @return int
     */
    public function countAll(array $filters = []): int;

    /**
     * @param int $first
     * @param int $maxResults
     *
     * @return array
     */
    public function getSliceAndFilteredResults(int $first, int $maxResults, array $filters = []): array;
}
