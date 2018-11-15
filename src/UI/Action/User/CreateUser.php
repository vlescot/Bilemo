<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Entity\User;
use App\UI\Action\User\Interfaces\CreateUserInterface;
use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
final class CreateUser implements CreateUserInterface
{
    /**
     * @var CreateEntityFactoryInterface
     */
    private $createFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(CreateEntityFactoryInterface $createFactory)
    {
        $this->createFactory = $createFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response
    {
        $user = $this->createFactory->create($request, User::class);

        return $responder($user, 'user', 'user_read');
    }
}
