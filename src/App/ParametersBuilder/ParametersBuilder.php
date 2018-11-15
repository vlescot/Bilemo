<?php
declare(strict_types=1);

namespace App\App\ParametersBuilder;

use App\App\ParametersBuilder\Interfaces\ParametersBuilderInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

/**
 * This class returns parameters built for specific method class
 * considering the public attributes of $object
 *
 *
 * Class ParametersBuilder
 * @package App\App\ParameterBuilder
 */
final class ParametersBuilder implements ParametersBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function BuildParameters($object, string $class, string $method): array
    {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('Must be an object', 500);
        }

        $reflectionExtractor = new ReflectionExtractor();
        $properties = $reflectionExtractor->getProperties($object);

        $reflectionMethod = new \ReflectionMethod($class, $method);
        $methodParameters = $reflectionMethod->getParameters();

        $parameters = [];

        foreach ($methodParameters as $key => $methodParameter) {
            $attribute = $methodParameter->getName();

            if (\in_array($attribute, $properties, true)) {
                $parameters[$key] = $object->$attribute;
            }
        }

        return $parameters;
    }
}
