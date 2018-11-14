<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Validator\ApiValidator;
use App\App\ErrorException\ApiError;
use App\App\ErrorException\ApiException;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\DTO\UserDTO;
use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\UpdateUserActionInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users/{id}",
 *     name="user_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdateUserAction
 * @package App\UI\Action\User
 */
final class UpdateUserAction implements UpdateUserActionInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ApiValidator
     */
    private $apiValidator;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

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
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response
    {
        $json = $request->getContent();

        $userId = $request->attributes->get('id');

        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'User', $userId));
        }

        try {
            $userDTO = $this->serializer->deserialize($json, UserDTO::class, 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $password = $this->passwordEncoder->encodePassword($user, $userDTO->password);

        $user->update(
            $password,
            $userDTO->email,
            $userDTO->phoneNumber
        );

        $this->apiValidator->validate($user, null, ['user']);

        $this->userRepository->save($user);

        $jsonUser = $this->serializer->serialize($user, 'json', ['groups' => ['user']]);

        return $responder($jsonUser, $user);
    }
}
