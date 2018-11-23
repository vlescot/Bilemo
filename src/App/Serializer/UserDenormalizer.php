<?php
declare(strict_types=1);

namespace App\App\Serializer;

use App\App\ErrorException\ApiError;
use App\App\ErrorException\ApiException;
use App\Domain\DTO\UserDTO;
use App\Domain\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class UserDenormalizer
 * @package App\App\Serializer
 */
final class UserDenormalizer implements DenormalizerInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * UserDenormalizer constructor.
     *
     * @param ClientRepository $clientRepository
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(
        ClientRepository $clientRepository,
        ObjectNormalizer $normalizer
    ) {
        $this->clientRepository = $clientRepository;
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     *
     * @return UserDTO
     */
    public function denormalize($data, $class, $format = null, array $context = array()): UserDTO
    {
        $userDTO = $this->normalizer->denormalize($data, $class, 'json', $context);

        if (isset($data['client']['username'])) {
            $clientUsername = $data['client']['username'];

            $client = $this->clientRepository->loadUserByUsername($clientUsername);

            if (null === $client) {
                $apiError = new ApiError(Response::HTTP_NOT_FOUND, ApiError::TYPE_CLIENT_NOT_FOUND);
                $apiError->set('Given username', $clientUsername);
                throw new ApiException($apiError);
            }

            $userDTO->client = $client;
        }

        return $userDTO;
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
        return UserDTO::class === $type;
    }
}
