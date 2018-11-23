<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUserListInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users",
 *     name="users_list",
 *     methods={"GET"}
 * )
 *
 * Class ReadUserList
 * @package App\UI\Action\User
 */
final class ReadUserList implements ReadUserListInterface
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
    public function __invoke(Request $request, UserInterface $user, ReadResponderInterface $responder) : Response
    {
        $clientId = $user->getId()->toString();

        $users = $this->userRepository->findUsersByClient($clientId);

        if (!$users) {
            throw new NotFoundHttpException(sprintf('Resources %s not found with client id "%s"', 'User', $clientId));
        }

        return $responder($users, 'users_list');
    }
}
