<?php
declare(strict_types=1);

namespace App\App\Pagination;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var int
     */
    private $itemsPerPage;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams;

    /**
     * PaginationFactory constructor.
     *
     * @param RouterInterface $router
     * @param int $itemsPerPage
     */
    public function __construct(
        RouterInterface $router,
        int $itemsPerPage
    ) {
        $this->router = $router;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param RepositoryAllowPaginationInterface $repository
     * @param Request $request
     * @param string $route
     * @param array $routeParams
     *
     * @return PaginatedCollection
     */
    public function createCollection(
        RepositoryAllowPaginationInterface $repository,
        Request $request,
        string $route,
        array $routeParams = []
    ) {
        $this->route = $route;
        $this->routeParams = $routeParams;

        $queryParams = $request->query->all();
        $page = (int) $request->query->get('page', 1);

        // Make sure query parameters are included in pagination links
        $this->routeParams = array_merge($routeParams, $queryParams);

        $paginator = new Paginator($repository, $page, $this->itemsPerPage, $queryParams);


        // Check if the page exists and return 404 Exception if false
        $nbPages = (int) ceil($paginator->getNbResults() / $this->itemsPerPage);
        if ($page > $nbPages) {
            $apiError = new ApiError(404, ApiError::TYPE_INVALID_REQUEST_FILTER_PAGINATION);
            if ($nbPages > 0) {
                $apiError->set('page_max', $this->createLinkUrl($nbPages));
            }
            $apiError->set('query_parameters', $this->routeParams);

            throw new ApiException($apiError);
        }


        $paginatedCollection = new PaginatedCollection(
            $paginator->getCurrentPageResults(),
            $paginator->getNbResults()
        );

        $paginatedCollection->addLink('first', $this->createLinkUrl(1));
        if ($paginator->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $this->createLinkUrl($paginator->getPreviousPage()));
        }
        $paginatedCollection->addLink('self', $this->createLinkUrl($page));
        if ($paginator->hasNextPage()) {
            $paginatedCollection->addLink('next', $this->createLinkUrl($paginator->getNextPage()));
        }
        $paginatedCollection->addLink('last', $this->createLinkUrl($paginator->getNbPages()));


        return $paginatedCollection;
    }

    /**
     * @param int $targetPage
     *
     * @return string
     */
    private function createLinkUrl(int $targetPage)
    {
        return $this->router->generate(
            $this->route,
            array_merge(
                $this->routeParams,
                ['page' => $targetPage]
            )
        );
    }
}
