<?php
declare(strict_types=1);

namespace App\App\Pagination\Interfaces;

use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Interface PaginationFactoryInterface
 * @package App\App\Pagination\Interfaces
 */
interface PaginationFactoryInterface
{
    /**
     * PaginationFactoryInterface constructor.
     *
     * @param RouterInterface $router
     * @param int $itemsPerPage
     */
    public function __construct(RouterInterface $router, int $itemsPerPage);

    /**
     * @param RepositoryAllowPaginationInterface $repository
     * @param Request $request
     * @param string $route
     * @param array $routeParams
     *
     * @return PaginatedCollectionInterface
     */
    public function createCollection(
        RepositoryAllowPaginationInterface $repository,
        Request $request,
        string $route,
        array $routeParams = []
    ): PaginatedCollectionInterface;
}
