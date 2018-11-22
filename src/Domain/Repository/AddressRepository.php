<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class AddressRepository extends ServiceEntityRepository
{
    /**
     * ClientRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Address::class);
    }
}
