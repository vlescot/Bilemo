<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Phone;
use App\Domain\Repository\Interfaces\RepositoryAllowPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhoneRepository extends ServiceEntityRepository implements RepositoryAllowPagination
{
    /**
     * PhoneRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    /**
     * @param array $filters
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAll(array $filters = []): int
    {
        $qb = $this->createQueryBuilder('p');
        $qb = $this->queryFilters($qb, $filters);

        return (int) $qb->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $first
     * @param int $maxResults
     * @param array $filters
     *
     * @return array
     */
    public function getSliceAndFilteredResults(int $first, int $maxResults, array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($maxResults);

        return $this->queryFilters($qb, $filters)
            ->setCacheable(true)
            ->getQuery()
            ->useResultCache(true)
            ->useQueryCache(true)
            ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param Phone $phone
     */
    public function save(Phone $phone)
    {
        $this->_em->persist($phone);
        $this->_em->flush();
    }

    /**
     * @param Phone $phone
     */
    public function remove(Phone $phone)
    {
        $this->_em->remove($phone);
        $this->_em->flush();
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function queryFilters(QueryBuilder $qb, array $filters = []): QueryBuilder
    {
        if (array_key_exists('brand', $filters) && array_key_exists('model', $filters)) {
            $qb ->andWhere('p.brand LIKE :brand')
                ->setParameter('brand', $filters['brand'])
                ->andWhere('p.model LIKE :model')
                ->setParameter('model', $filters['model']);
        } elseif (array_key_exists('brand', $filters)) {
            $qb ->andWhere('p.brand LIKE :brand')
                ->setParameter('brand', $filters['brand']);
        } elseif (array_key_exists('model', $filters)) {
            $qb ->andWhere('p.model LIKE :model')
                ->setParameter('model', $filters['model']);
        }

        return $qb;
    }
}
