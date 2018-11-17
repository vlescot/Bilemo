<?php
declare(strict_types=1);

namespace App\UI\Factory;

use App\App\ErrorException\ApiError;
use App\App\ErrorException\ApiException;
use App\App\ParametersBuilder\Interfaces\ParametersBuilderInterface;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\DTO\PhoneDTO;
use App\Domain\DTO\UserDTO;
use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UpdateEntityFactory
 * @package App\UI\Factory
 */
final class UpdateEntityFactory implements UpdateEntityFactoryInterface
{
    private const ENTITY_DTO = [
        Phone::class => PhoneDTO::class,
        User::class => UserDTO::class
    ];

    private const ENTITY_STRING = [
        Phone::class => 'phone',
        User::class => 'user'
    ];

    /**
     * @var SerializerInterface
     */
    private $serializer;

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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidatorInterface $apiValidator,
        EntityManagerInterface $em,
        ParametersBuilderInterface $parametersBuilder,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->em = $em;
        $this->parametersBuilder = $parametersBuilder;
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * {@inheritdoc}
     */
    public function update(Request $request, string $entityName)
    {
        $json = $request->getContent();
        $entityId = $request->attributes->get('id');

        $repository = $this->em->getRepository($entityName);
        $entity = $repository->findOneById($entityId);

        if (!$entity) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', self::ENTITY_STRING[$entityName], $entityId));
        }

        try {
            $dto = $this->serializer->deserialize($json, self::ENTITY_DTO[$entityName], 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        if (property_exists($dto, 'password')) {
            $dto->password = $this->passwordEncoder->encodePassword($entity, $dto->password);
        }

        $params = $this->parametersBuilder->BuildParameters($dto, $entityName, 'update');
        call_user_func_array([$entity, 'update'], $params);

        $this->apiValidator->validate($entity, null, [ self::ENTITY_STRING[$entityName] ]);

        $repository->save($entity);

        return $entity;
    }
}