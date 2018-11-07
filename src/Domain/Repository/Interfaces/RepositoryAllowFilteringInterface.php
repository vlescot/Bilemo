<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

interface RepositoryAllowFilteringInterface
{
    /**
     * @param int $first
     * @param int $maxResults
     * @param array $filters
     *
     * @return array
     */
    public function getSliceAndFilteredResults(int $first, int $maxResults, array $filters = []): array;
}
