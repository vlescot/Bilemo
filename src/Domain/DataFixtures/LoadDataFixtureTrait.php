<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use Symfony\Component\Yaml\Yaml;

// TODO VERIFIER LES REFERENCES DANS TOUTES LES ENTITES, CERTAINES VONT DEVENIR INUTILES

trait LoadDataFixtureTrait
{
    /**
     * @param string $entityName
     *
     * @return array
     */
    public function getDataFixture(string $entityName) :array
    {
        return Yaml::parse(file_get_contents(__DIR__.'/Fixtures/'. $entityName .'.yaml', true));
    }
}
