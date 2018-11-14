<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\Repository\UserRepository;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface CreateUserActionInterface
 * @package App\UI\Action\User\Interfaces
 */
interface CreateUserActionInterface
{
    /**
     * CreateUserActionInterface constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidatorInterface $apiValidator
     * @param UserRepository $userRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidatorInterface $apiValidator,
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $passwordEncoder
    );

    /**
     * @param Request $request
     * @param CreateResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response;
}
