<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Manufacturer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ManufacturerRepository
 * @package App\Domain\Repository
 */
final class ManufacturerRepository extends ServiceEntityRepository
{
    /**
     * PhoneRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Manufacturer::class);
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByName(string $name)
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :name')
            ->setParameter('name', $name)
//            ->setCacheable(true)
            ->getQuery()
//            ->useResultCache(true)
//            ->useQueryCache(true)
            ->getOneOrNullResult();
    }
}
