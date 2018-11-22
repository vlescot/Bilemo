<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Entity\Client;
use App\UI\Action\Client\Interfaces\CreateClientInterface;
use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/clients",
 *     name="client_create",
 *     methods={"POST"}
 * )
 *
 * Class CreateClient
 * @package App\UI\Action\Client
 */
final class CreateClient implements CreateClientInterface
{
    /**
     * @var CreateEntityFactoryInterface
     */
    private $createFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(CreateEntityFactoryInterface $createFactory)
    {
        $this->createFactory = $createFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response
    {
        $client = $this->createFactory->create($request, Client::class);

        return $responder($client, 'client', 'client_read');
    }
}
