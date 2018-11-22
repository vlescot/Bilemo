<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Entity\User;
use App\UI\Action\User\Interfaces\UpdateUserInterface;
use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/users/{id}",
 *     name="user_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdateUser
 * @package App\UI\Action\User
 */
final class UpdateUser implements UpdateUserInterface
{
    /**
     * @var UpdateEntityFactoryInterface
     */
    private $updateFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(UpdateEntityFactoryInterface $updateFactory)
    {
        $this->updateFactory = $updateFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response
    {
        $client = $this->updateFactory->update($request, User::class);

        return $responder($client, 'user', 'user_read');
    }
}
