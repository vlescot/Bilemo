<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * ClientRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @param string $clientId
     *
     * @return null|object
     */
    public function findOneById(string $clientId): ? Client
    {
        return parent::findOneBy(['id' => $clientId]);
    }

    /**
     * @param $clientname
     * @return null|UserInterface
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username): ? UserInterface
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $clientId
     * @return null|string
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOneUpdateDate(string $clientId): ? string
    {
        return $this->createQueryBuilder('u')
            ->select('u.updatedAt')
            ->where('u.id LIKE :id')
            ->setParameters(['id' => $clientId])
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
     * @param UserInterface $client
     */
    public function save(UserInterface $client)
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
