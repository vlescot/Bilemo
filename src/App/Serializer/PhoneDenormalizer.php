<?php
declare(strict_types=1);

namespace App\App\Serializer;

use App\Domain\Entity\Phone;
use App\Domain\Repository\ManufacturerRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class PhoneDenormalizer implements DenormalizerInterface
{
    /**
     * @var ManufacturerRepository
     */
    private $manufacturerRepository;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * PhoneDenormalizer constructor.
     *
     * @param ManufacturerRepository $manufacturerRepository
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(
        ManufacturerRepository $manufacturerRepository,
        ObjectNormalizer $normalizer
    ) {
        $this->manufacturerRepository = $manufacturerRepository;
        $this->normalizer = $normalizer;
    }


    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     *
     * @return object
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $phone = $this->normalizer->denormalize($data, Phone::class, 'json');

        $manufacturerName = $data['manufacturer']['name'];
        $manufacturer = $this->manufacturerRepository->findOneByName($manufacturerName);

        if (null !== $manufacturer) {
            $phone->setManufacturer($manufacturer);
        }

        return $phone;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return Phone::class === $type;
    }
}