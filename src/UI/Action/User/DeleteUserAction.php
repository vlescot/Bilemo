<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Repository\UserRepository;
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
final class DeleteUserAction
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * DeleteUserAction constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get('id');

        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $userId));
        }

        $this->userRepository->remove($user);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
