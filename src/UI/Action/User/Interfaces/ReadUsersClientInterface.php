<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\App\Pagination\Interfaces\PaginationFactoryInterface;
use App\Domain\Repository\UserRepository;
use App\UI\Responder\Interfaces\ReadResponderInterface;
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
     * @param PaginationFactoryInterface $paginationFactor
     * @param SerializerInterface $serializer
     */
    public function __construct(
        UserRepository $userRepository,
        PaginationFactoryInterface $paginationFactor,
        SerializerInterface $serializer
    );

    /**
     * @param Request $request
     * @param ReadResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, ReadResponderInterface $responder) : Response;
}
