<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUserInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
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
final class ReadUser implements ReadUserInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function __invoke(Request $request, ReadResponderInterface $responder): Response
    {
        $userId = $request->attributes->get('id');

        $userLastUpdateDate = $this->userRepository->getOneUpdateDate($userId);

        if (!$userLastUpdateDate) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'User', $userId));
        }

        $lastModified = new \DateTime();
        $lastModified->setTimestamp(intval($userLastUpdateDate));

        $response = new Response();
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        $user = $this->userRepository->findOneById($userId);

        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user']]);

        $response->setStatusCode(Response::HTTP_OK);
        return $response->setContent($json);

//        return $responder($json);
    }
}
