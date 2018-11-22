<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadUsersClientInterface
 * @package App\UI\Action\User\Interfaces
 */
interface ReadUsersClientInterface
{
    /**
     * ReadUsersClientInterface constructor.
     *
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(UserRepository $userRepository, SerializerInterface $serializer);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request) : Response;
}
