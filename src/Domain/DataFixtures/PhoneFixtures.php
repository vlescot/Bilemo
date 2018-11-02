<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class PhoneFixtures extends Fixture
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
                $phoneEntity = new Phone(
                    $phone['Brand'],
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
}
