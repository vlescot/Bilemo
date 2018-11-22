<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Repository\ClientRepository;
use App\UI\Action\Client\Interfaces\ReadClientListInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/clients",
 *     name="clients_list",
 *     methods={"GET"}
 * )
 *
 * Class Client
 * @package App\UI\Action\Client
 */
final class ReadClientList implements ReadClientListInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClientRepository $clientRepository,
        SerializerInterface $serializer
    ) {
        $this->clientRepository = $clientRepository;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request): Response
    {
        $timestamp = $this->clientRepository->getLastUpdateDate();
        $lastModified = new \DateTime();
        $lastModified->setTimestamp(intval($timestamp));

        $response = new Response();
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        $clients = $this->clientRepository->findAll();

        $json  = $this->serializer->serialize($clients, 'json', ['groups' => ['clients_list']]);

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/hal+json');
        return $response->setContent($json);
    }
}
