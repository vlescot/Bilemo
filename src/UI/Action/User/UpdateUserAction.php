<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Validator\ApiValidator;
use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\DTO\UserDTO;
use App\Domain\Repository\UserRepository;
use App\UI\Responder\UpdateResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
final class UpdateUserAction
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
     * UpdateUserAction constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidator $apiValidator
     * @param UserRepository $userRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidator $apiValidator,
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
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request, UpdateResponder $responder): Response
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
            $userDTO->email
        );

        $this->apiValidator->validate($user, null, ['user']);

        $this->userRepository->save($user);

        $jsonUser = $this->serializer->serialize($user, 'json', ['groups' => ['user']]);

        return $responder($jsonUser, $user);
    }
}
