<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUserLiserActionInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
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
final class ReadUserListAction implements ReadUserLiserActionInterface
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
    public function __invoke(ReadResponderInterface $responder): Response
    {
        $users = $this->userRepository->findAll();

        $json  = $this->serializer->serialize($users, 'json', ['groups' => ['user']]);

        return $responder($json);
    }
}
