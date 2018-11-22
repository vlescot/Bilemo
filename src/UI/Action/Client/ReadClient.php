<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Entity\Client;
use App\UI\Action\Client\Interfaces\ReadClientInterface;
use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/clients/{id}",
 *     name="client_read",
 *     methods={"GET"}
 * )
 *
 * Class ReadClient
 * @package App\UI\Action\Client
 */
final class ReadClient implements ReadClientInterface
{
    /**
     * @var ReadEntityFactoryInterface
     */
    private $readFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(ReadEntityFactoryInterface $readFactory)
    {
        $this->readFactory = $readFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request): Response
    {
        return $this->readFactory->read($request, Client::class);
    }
}
