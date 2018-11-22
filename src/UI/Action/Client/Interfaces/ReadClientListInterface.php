<?php
declare(strict_types=1);

namespace App\UI\Action\Client\Interfaces;

use App\Domain\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadClientListInterface
 * @package App\UI\Action\Client\Interfaces
 */
interface ReadClientListInterface
{
    /**
     * ReadClientListInterface constructor.
     *
     * @param ClientRepository $userRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ClientRepository $userRepository,
        SerializerInterface $serializer
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
