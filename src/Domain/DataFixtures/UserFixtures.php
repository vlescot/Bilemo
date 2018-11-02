<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    use LoadDataFixtureTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = $this->getDataFixture('User');

        foreach ($users as $reference => $user) {
            $userEntity = new User();

            $password = $this->passwordEncoder->encodePassword($userEntity, $user['Password']);

            $userEntity->registration(
                $user['Roles'],
                $user['Username'],
                $password,
                $user['Email']
            );

            $this->addReference($reference, $userEntity);
            $manager->persist($userEntity);
        }

        $manager->flush();
    }
}
