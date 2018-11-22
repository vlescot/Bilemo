<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Address;
use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture implements DependentFixtureInterface
{
    use LoadDataFixtureTrait;

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
        $address = $this->getDataFixture('Address');

        $nb = 0;
        while ($nb < 42) {
            $name = 'User' . $nb;
            $email = $name . '@gmail.com';
            $phoneNumber = '0'. rand(100000000, 599999999);
            $client = $this->getReference('client_'. rand(0, 3));

            $randomAddress = 'address_'. rand(0, 5);

            $userAddress = new Address(
                $address[$randomAddress]['street_address'],
                $address[$randomAddress]['city'],
                $address[$randomAddress]['postcode']
            );


            $userEntity = new User();
            $userEntity->create($name, $phoneNumber, $email, $userAddress, $client);

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
