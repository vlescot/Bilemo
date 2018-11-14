<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Manufacturer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class ManufacturerFixtures extends Fixture
{
    use LoadDataFixtureTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manufacturers = $this->getDataFixture('Manufacturer');

        foreach ($manufacturers as $name => $manufacturer) {
            $manufacturerEntity = new Manufacturer(
                $manufacturer['Name']
            );

            $manager->persist($manufacturerEntity);
            $this->addReference($name, $manufacturerEntity);
        }
        $manager->flush();
    }
}
