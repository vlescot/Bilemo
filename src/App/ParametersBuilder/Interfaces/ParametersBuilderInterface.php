<?php
declare(strict_types=1);

namespace App\App\ParametersBuilder\Interfaces;

/**
 * Interface ParametersBuilderInterface
 * @package App\App\ParametersBuilder\Interfaces
 */
interface ParametersBuilderInterface
{
    /**
     * @param $object
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    public function BuildParameters($object, string $class, string $method): array;
}
