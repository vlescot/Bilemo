<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Responder\ReadResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users",
 *     name="users_list",
 *     methods={"GET"}
 * )
 *
 * Class UserAction
 * @package App\UI\Action\User
 */
final class ReadUserListAction
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
     * UserAction constructor.
     *
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param ReadResponder $responder
     *
     * @return Response
     */
    public function __invoke(ReadResponder $responder): Response
    {
        $users = $this->userRepository->findAll();

        $json  = $this->serializer->serialize($users, 'json', ['groups' => ['user']]);

        return $responder($json);
    }
}
