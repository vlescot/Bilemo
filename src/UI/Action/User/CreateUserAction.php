<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Validator\ApiValidator;
use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * CreateUserAction constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidator $apiValidator
     * @param UserRepository $userRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidator $apiValidator,
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke(Request $request)
    {
        $json = $request->getContent();

        try {
            $user = $this->serializer->deserialize($json, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $this->apiValidator->validate($user, null, ['user']);

        $this->userRepository->save($user);

        $jsonUser = $this->serializer->serialize($user, 'json', ['groups' => ['user']]);

        return new Response($jsonUser, Response::HTTP_CREATED, [
                'location' => $this->urlGenerator->generate('user_read', ['id' => $user->getId()] )
            ]
        );
    }
}
