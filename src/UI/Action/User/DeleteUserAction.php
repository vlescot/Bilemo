<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
use App\UI\Action\User\Interfaces\DeleteUserActionInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
final class DeleteUserAction implements DeleteUserActionInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response
    {
        $userId = $request->attributes->get('id');

        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $userId));
        }

        $this->userRepository->remove($user);

        return $responder();
    }
}
