<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUserListInterface;
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
 * Class User
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
    public function __invoke(Request $request): Response
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
        $response->headers->set('Content-Type', 'application/hal+json');
        return $response->setContent($json);
    }
}
