<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

interface RepositoryAllowPaginationInterface
{
    /**
     * @param array $filters
     *
     * @return int
     */
    public function countAll(array $filters = []): int;
}
