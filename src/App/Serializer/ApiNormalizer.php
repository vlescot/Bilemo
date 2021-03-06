<?php
declare(strict_types=1);

namespace App\App\Serializer;

use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use App\Domain\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ApiNormalizer
 * @package App\App\Normalizer
 */
final class ApiNormalizer implements NormalizerInterface
{
    private const ENTITY_STRING = [
        Phone::class => 'phone',
        Client::class => 'client',
        User::class => 'user'
    ];

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * ApiNormalizer constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ObjectNormalizer $normalizer
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $object
     * @param null $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $this->normalizer->setCircularReferenceLimit(2);
        $data = $this->normalizer->normalize($object, $format, $context);

        if (isset($data['createdAt'])) {
            $data['createdAt'] = date('Y/m/d h:i A', $data['createdAt']);
        }

        $data['_links'] = $this->generateHalLinks($object);

        return $data;
    }

    /**
     * @param $entity
     *
     * @return array
     */
    private function generateHalLinks($entity): array
    {
        $routeNamePrefix = self::ENTITY_STRING[get_class($entity)];

        $links['self']['href'] = $this->generateRoute($entity, $routeNamePrefix .'_read');

        return $links;
    }

    /**
     * @param $entity
     * @param string $routeName
     *
     * @return string
     */
    private function generateRoute($entity, string $routeName): string
    {
        return $this->urlGenerator->generate(
            $routeName,
            ['id' => $entity->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @param mixed $data
     * @param null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        if (!\is_object($data)) {
            return false;
        }

        switch (get_class($data)) {
            case Phone::class:
                $result = true;
                break;

            case Client::class:
                $result = true;
                break;

            case User::class:
                $result = true;
                break;

            default:
                $result = false;
        }

        return $result;
    }
}
