<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Entity\Client;
use App\UI\Action\Client\Interfaces\UpdateClientInterface;
use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/clients/{id}",
 *     name="client_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdateClient
 * @package App\UI\Action\Client
 */
final class UpdateClient implements UpdateClientInterface
{
    /**
     * @var UpdateEntityFactoryInterface
     */
    private $updateFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(UpdateEntityFactoryInterface $updateFactory)
    {
        $this->updateFactory = $updateFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response
    {
        $client = $this->updateFactory->update($request, Client::class);

        return $responder($client, 'client', 'client_read');
    }
}
