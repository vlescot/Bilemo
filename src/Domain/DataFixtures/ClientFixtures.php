<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ClientFixtures
 * @package App\Domain\DataFixtures
 */
final class ClientFixtures extends Fixture
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

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $clients = $this->getDataFixture('Client');

        foreach ($clients as $reference => $client) {
            $clientEntity = new Client();

            $password = $this->passwordEncoder->encodePassword($clientEntity, $client['Password']);

            $clientEntity->create(
                $client['Username'],
                $password,
                $client['Email'],
                '0' . rand(00000000, 999999999)
            );

            $this->addReference($reference, $clientEntity);
            $manager->persist($clientEntity);
        }

        $manager->flush();
    }
}
