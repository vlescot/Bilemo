<?php
declare(strict_types=1);

namespace App\App\Normalizer;

use App\App\Pagination\PaginatedCollection;
use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\DateTime;

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
                $data['_link'] = ['href' => $this->generatePhoneRoute($object)];
                break;

            case User::class:
                $data['_link'] = ['href' => $this->generateUserRoute($object)];
                break;
        }

        return $data;
    }

    /**
     * @param Phone $phone
     *
     * @return string
     */
    public function generatePhoneRoute(Phone $phone)
    {
        return $this->urlGenerator->generate('phone_read', [
            'brand' => $phone->getBrand(),
            'model' => $phone->getModel()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function generateUserRoute(User $user)
    {
        return $this->urlGenerator->generate('user_read', [
            'username' => $user->getUsername()
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
//        dump('Api Normalizer');dump($data);
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
