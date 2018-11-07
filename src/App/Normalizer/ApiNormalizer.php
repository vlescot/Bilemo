<?php
declare(strict_types=1);

namespace App\App\Normalizer;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ApiNormalizer implements NormalizerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var string
     */
    private $class;

    /**
     * LinkNormalizer constructor.
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
        $data = $this->normalizer->normalize($object, $format, $context);

        if (isset($data['createdAt'])) {
            $data['createdAt'] = date('Y/m/d h:i A', $data['createdAt']);
        }

        switch ($this->class) {
            case Phone::class:
                $data['_link'] = ['href' => $this->generateRoute($object, 'phone')];
                break;

            case User::class:
                $data['_link'] = ['href' => $this->generateRoute($object, 'user')];
                break;
        }

        return $data;
    }

    /**
     * @param $entity
     * @param string $class
     *
     * @return string
     */
    public function generateRoute($entity, string $class)
    {
        $routeName = $class . '_read';

        return $this->urlGenerator->generate($routeName, [
            'id' => $entity->getId()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param mixed $data
     * @param null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        if (!\is_object($data)) {
            return false;
        }

        switch (get_class($data)) {
            case Phone::class:
                $this->class = Phone::class;
                $result = true;
                break;

            case User::class:
                $this->class = User::class;
                $result = true;
                break;

            default:
                $result = false;
        }

        return $result;
    }
}
