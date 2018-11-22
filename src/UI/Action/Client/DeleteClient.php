<?php
declare(strict_types=1);

namespace App\UI\Action\Client;

use App\Domain\Entity\Client;
use App\UI\Action\Client\Interfaces\DeleteClientInterface;
use App\UI\Factory\Interfaces\DeleteEntityFactoryInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/clients/{id}",
 *     name="client_delete",
 *     methods={"DELETE"}
 * )
 *
 * Class DeleteClient
 * @package App\UI\Action\Client
 */
final class DeleteClient implements DeleteClientInterface
{
    /**
     * @var DeleteEntityFactoryInterface
     */
    private $deleteFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(DeleteEntityFactoryInterface $deleteFactory)
    {
        $this->deleteFactory = $deleteFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response
    {
        $this->deleteFactory->delete($request, Client::class);

        return $responder();
    }
}
