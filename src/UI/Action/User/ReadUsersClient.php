<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\App\Pagination\Interfaces\PaginationFactoryInterface;
use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\ReadUsersClientInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/users-list",
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
     * @var PaginationFactoryInterface
     */
    private $paginationFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        UserRepository $userRepository,
        PaginationFactoryInterface $paginationFactory,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->paginationFactory = $paginationFactory;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, ReadResponderInterface $responder): Response
    {
        $route = $request->attributes->get('_route');

        $paginatedCollection = $this->paginationFactory->createCollection(
            $this->userRepository,
            $request,
            $route
        );

        return $responder($paginatedCollection, 'users_client_list');
    }
}
