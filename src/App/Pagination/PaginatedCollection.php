<?php
declare(strict_types=1);

namespace App\App\Pagination;

/**
 * Class PaginatedCollection
 * @package App\App\Pagination
 */
final class PaginatedCollection
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
    private $phones;


    /**
     * PaginatedCollection constructor.
     *
     * @param array $phones
     * @param int $total
     */
    public function __construct(array $phones, int $total)
    {
        $this->phones = $phones;
        $this->total = $total;
        $this->limit = \count($phones);
    }

    /**
     * @param $ref
     * @param $url
     */
    public function addLink($ref, $url)
    {
        $this->_links[$ref] = $url;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->_links;
    }

    /**
     * @return array
     */
    public function getPhones(): array
    {
        return $this->phones;
    }
}
