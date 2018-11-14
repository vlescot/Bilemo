<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\Domain\Repository\UserRepository;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface ReadUserInterface
{
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        UserRepository $userRepository,
        SerializerInterface $serializer
    );
    public function __invoke(Request $request, ReadResponderInterface $responder): Response;
}
