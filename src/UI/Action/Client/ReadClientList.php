<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Repository\ClientRepository;
use App\UI\Action\Client\Interfaces\ReadClientListInterface;
use App\UI\Responder\Interfaces\CacheReadResponderInterface;
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
 * Class ReadClientList
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
    public function __invoke(Request $request, CacheReadResponderInterface $responder): Response
    {
        $timestamp = $this->clientRepository->getLastUpdateDate();

        $responder->buildCache(intval($timestamp));

        if ($responder->isCacheValid($request)) {
            return $responder->getResponse();
        }

        $clients = $this->clientRepository->findAll();

        return $responder->createResponse($clients, 'clients_list');
    }
}
