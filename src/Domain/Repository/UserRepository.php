<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\RepositoryAllowFilteringInterface;
use App\Domain\Repository\Interfaces\RepositoryAllowPaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository
 * @package App\Domain\Repository
 */
final class UserRepository extends ServiceEntityRepository implements
    RepositoryAllowPaginationInterface,
    RepositoryAllowFilteringInterface
{
    /**
     * ClientRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $clientId
     *
     * @return array|null
     */
    public function findUsersByClient(string $clientId): ? array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.client', 'c')
            ->andWhere('c.id LIKE :client_id')
            ->setParameter('client_id', $clientId)
            ->setCacheable(true)
            ->getQuery()
            ->useQueryCache(true)
            ->getResult(Query::HYDRATE_OBJECT);
    }


    /**
     * @param string $clientId
     *
     * @return null|object
     */
    public function findOneById(string $clientId): ? User
    {
        return parent::findOneBy(['id' => $clientId]);
    }


    /**
     * @param string $userId
     * @return null|string
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOneUpdateDate(string $userId): ? string
    {
        return $this->createQueryBuilder('u')
            ->select('u.updatedAt')
            ->where('u.id LIKE :id')
            ->setParameters(['id' => $userId])
            ->setCacheable(true)
            ->getQuery()
            ->useQueryCache(true)
            ->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @return null|string
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastUpdateDate(): ? string
    {
        return $this->createQueryBuilder('u')
            ->select('MAX(u.updatedAt) as lastUpdate')
            ->setCacheable(true)
            ->getQuery()
            ->useQueryCache(true)
            ->getSingleScalarResult();
    }

    /**
     * @param array $filters
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAll(array $filters = []): int
    {
        $qb = $this->createQueryBuilder('u');
        $qb = $this->queryFilters($qb, $filters);

        return (int) $qb->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function queryFilters(QueryBuilder $qb, array $filters = []): QueryBuilder
    {
        if (array_key_exists('client', $filters)) {
            $qb ->leftJoin('u.client', 'c')
                ->andWhere('c.username LIKE :client_name')
                ->setParameter('client_name', $filters['client']);
        }
        return $qb;
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
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($maxResults);

        return $this->queryFilters($qb, $filters)
            ->setCacheable(true)
            ->getQuery()
            ->useQueryCache(true)
            ->getResult(Query::HYDRATE_OBJECT);
    }


    /**
     * @param User $client
     */
    public function save(User $client)
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }

    /**
     * @param string $clientId
     *
     * @return bool
     */
    public function remove(string $clientId): bool
    {
        $client = $this->findOneById($clientId);

        if (null === $client) {
            return false;
        }

        $this->_em->remove($client);
        $this->_em->flush();

        return true;
    }
}
