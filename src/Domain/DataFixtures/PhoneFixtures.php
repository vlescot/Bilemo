<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PhoneFixtures
 * @package App\Domain\DataFixtures
 */
final class PhoneFixtures extends Fixture implements DependentFixtureInterface
{
    use LoadDataFixtureTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $phones = $this->getDataFixture('Phone');

        $n = 0;
        $nb = 0;
        while ($n < 10) {
            foreach ($phones as $phone) {
                $manufacturer = $this->getReference($phone['Manufacturer']);

                $phoneEntity = new Phone();
                $phoneEntity->create(
                    $manufacturer,
                    $phone['Model'] . $nb,
                    $phone['Description'],
                    $phone['Price'],
                    $phone['Stock']
                );

                $nb++;

                $manager->persist($phoneEntity);
            }
            $n++;
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            ManufacturerFixtures::class
        ];
    }
}
