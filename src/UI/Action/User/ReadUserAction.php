<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Repository\UserRepository;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users/{id}",
 *     name="user_read",
 *     methods={"GET"}
 * )
 *
 * Class ReadUserAction
 * @package App\UI\Action\User
 */
final class ReadUserAction extends AbstractController
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ReadUserAction constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param UserRepository $userRepository
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        UserRepository $userRepository,
        SerializerInterface $serializer
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(Request $request)
    {
//        $this->isGranted('ROLE_SELF_USER', $request);

        $userId = $request->attributes->get('id');

        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $userId));
        }

        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user']]);

        return new Response($json, Response::HTTP_OK);
    }
}
