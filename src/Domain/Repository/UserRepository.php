<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $id
     *
     * @return null|object
     */
    public function findOneById(string $id): ? User
    {
        return parent::findOneBy(['id' => $id]);
    }

    /**
     * @param $username
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
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
