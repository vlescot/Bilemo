<?php
declare(strict_types=1);

namespace App\Domain\DataFixtures;

use Symfony\Component\Yaml\Yaml;

/**
 * Trait LoadDataFixtureTrait
 * @package App\Domain\DataFixtures
 */
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
