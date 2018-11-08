<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;
use App\Domain\Repository\UserRepository;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadUserLiserActionInterface
 * @package App\UI\Action\User\Interfaces
 */
interface ReadUserLiserActionInterface
{
    /**
     * ReadUserLiserActionInterface constructor.
     *
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer
    );

    /**
     * @param ReadResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(ReadResponderInterface $responder): Response;
}
