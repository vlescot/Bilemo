<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * ClientFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $nb = 0;
        while ($nb < 42) {
            $username = 'user' . $nb;
            $password = 'password' . $nb;
            $email = $username . '@gmail.com';
            $phoneNumber = '0'. rand(100000000, 599999999);
            $client = $this->getReference('client_'. rand(0, 3));

            $userEntity = new User();

            $password = $this->passwordEncoder->encodePassword($userEntity, $password);

            $userEntity->create($username, $password, $email, $phoneNumber, $client);

            $manager->persist($userEntity);

            $nb++;
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            ClientFixtures::class
        ];
    }
}
