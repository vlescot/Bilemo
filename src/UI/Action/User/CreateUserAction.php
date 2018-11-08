<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Validator\ApiValidator;
use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\DTO\UserDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\UI\Responder\CreateResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users",
 *     name="user_create",
 *     methods={"POST"}
 * )
 *
 * Class CreateUserAction
 * @package App\UI\Action\User
 */
final class CreateUserAction
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
     * CreateUserAction constructor.
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
     * @param CreateResponder $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CreateResponder $responder): Response
    {
        $json = $request->getContent();

        try {
            $userDTO = $this->serializer->deserialize($json, UserDTO::class, 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $userDTO->password);

        $user->registration(
            $userDTO->username,
            $password,
            $userDTO->email
        );

        $this->apiValidator->validate($user, null, ['user']);

        // TODO Verifeir si passEncoder
//        dump($user);

        $this->userRepository->save($user);

        $jsonUser = $this->serializer->serialize($user, 'json', [
            'groups' => ['user']
        ]);

        return $responder($jsonUser, $user);
    }
}
