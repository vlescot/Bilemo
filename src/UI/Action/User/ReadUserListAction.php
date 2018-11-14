<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUserListActionInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
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
final class ReadUserListAction implements ReadUserListActionInterface
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
    public function __invoke(Request $request, ReadResponderInterface $responder): Response
    {
        $timestamp = $this->userRepository->getLastUpdateDate();
        $lastModified = new \DateTime();
        $lastModified->setTimestamp(intval($timestamp));

        $response = new Response();
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        $users = $this->userRepository->findAll();

        $json  = $this->serializer->serialize($users, 'json', ['groups' => ['users_list']]);

        $response->setStatusCode(200);
        return $response->setContent($json);

//        return $responder($json);
    }
}
