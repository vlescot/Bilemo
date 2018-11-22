<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUsersClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users/client/{client_id}",
 *     name="users_client_read",
 *     methods={"GET"}
 * )
 *
 * Class ReadUsersClient
 * @package App\UI\Action\User
 */
final class ReadUsersClient implements ReadUsersClientInterface
{
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
        UserRepository $userRepository,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request) : Response
    {
        $clientId = $request->attributes->get('client_id');

        $users = $this->userRepository->findUsersByClient($clientId);

        if (!$users) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with client id "%s"', 'User', $clientId));
        }

        $json  = $this->serializer->serialize($users, 'json', ['groups' => ['users_list']]);

        return new Response($json, Response::HTTP_OK);
    }
}
