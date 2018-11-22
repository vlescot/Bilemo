<?php
declare(strict_types=1);

namespace App\UI\Factory;

use App\App\ErrorException\ApiError;
use App\App\ErrorException\ApiException;
use App\App\ParametersBuilder\Interfaces\ParametersBuilderInterface;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\DTO\PhoneDTO;
use App\Domain\DTO\ClientDTO;
use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateEntityFactory implements CreateEntityFactoryInterface
{
    private const ENTITY_DTO = [
        Phone::class => PhoneDTO::class,
        Client::class => ClientDTO::class
    ];

    private const ENTITY_STRING = [
        Phone::class => 'phone',
        Client::class => 'client'
    ];


    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ApiValidatorInterface
     */
    private $apiValidator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ParametersBuilderInterface
     */
    private $parametersBuilder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        SerializerInterface $serializer,
        UserPasswordEncoderInterface $passwordEncoder,
        ApiValidatorInterface $apiValidator,
        EntityManagerInterface $em,
        ParametersBuilderInterface $parametersBuilder
    ) {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->apiValidator = $apiValidator;
        $this->em = $em;
        $this->parametersBuilder = $parametersBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Request $request, string $entityName)
    {
        $json = $request->getContent();

        try {
            $dto = $this->serializer->deserialize($json, self::ENTITY_DTO[$entityName], 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $entity = new $entityName();

        if (property_exists($dto, 'password')) {
            $dto->password = $this->passwordEncoder->encodePassword($entity, $dto->password);
        }

        $params = $this->parametersBuilder->BuildParameters($dto, $entityName, 'create');
        call_user_func_array([$entity, 'create'], $params);

        $this->apiValidator->validate($entity, null, [ self::ENTITY_STRING[$entityName] ]);

        $repository = $this->em->getRepository($entityName);
        $repository->save($entity);

        return $entity;
    }
}
