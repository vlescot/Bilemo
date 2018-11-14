<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\Domain\Repository\UserRepository;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DeleteUserActionInterface
 * @package App\UI\Action\User\Interfaces
 */
interface DeleteUserInterface
{
    /**
     * DeleteUserActionInterface constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository);

    /**
     * @param Request $request
     * @param DeleteResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response;
}
