<?php
declare(strict_types=1);

namespace App\App\Pagination;

final class PaginatedCollection
{
    /**
     * @var array
     */
    private $phone;

    /**
     * @var
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
     * PaginatedCollection constructor.
     *
     * @param array $phone
     * @param int $total
     */
    public function __construct(array $phone, int $total)
    {
        $this->phone = $phone;
        $this->total = $total;
        $this->limit = \count($phone);
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
        return $this->phone;
    }
}
