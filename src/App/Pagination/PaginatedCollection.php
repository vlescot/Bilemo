<?php
declare(strict_types=1);

namespace App\App\Pagination;

use App\App\Pagination\Interfaces\PaginatedCollectionInterface;

/**
 * Class PaginatedCollection
 * @package App\App\Pagination
 */
final class PaginatedCollection implements PaginatedCollectionInterface
{
    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var array
     */
    private $_links = [];

    /**
     * @var array
     */
    private $objects;


    /**
     * {@inheritdoc}
     */
    public function __construct(array $objects, int $total)
    {
        $this->objects = $objects;
        $this->total = $total;
        $this->limit = \count($objects);
    }

    /**
     * {@inheritdoc}
     */
    public function addLink($ref, $url)
    {
        $this->_links[$ref] = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks(): array
    {
        return $this->_links;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjects(): array
    {
        return $this->objects;
    }
}
