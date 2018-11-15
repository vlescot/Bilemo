<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Entity\User;
use App\UI\Action\User\Interfaces\DeleteUserInterface;
use App\UI\Factory\Interfaces\DeleteEntityFactoryInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/users/{id}",
 *     name="user_delete",
 *     methods={"DELETE"}
 * )
 *
 * Class DeleteUserAction
 * @package App\UI\Action\User
 */
final class DeleteUser implements DeleteUserInterface
{
    /**
     * @var DeleteEntityFactoryInterface
     */
    private $deleteFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(DeleteEntityFactoryInterface $deleteFactory)
    {
        $this->deleteFactory = $deleteFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response
    {
        $this->deleteFactory->delete($request, User::class);

        return $responder();
    }
}
