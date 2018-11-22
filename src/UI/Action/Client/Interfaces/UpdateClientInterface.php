<?php
declare(strict_types=1);

namespace App\UI\Action\Client\Interfaces;

use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface UpdateClientInterface
 * @package App\UI\Action\Client\Interfaces
 */
interface UpdateClientInterface
{
    /**
     * UpdateClientInterface constructor.
     *
     * @param UpdateEntityFactoryInterface $updateFactory
     */
    public function __construct(UpdateEntityFactoryInterface $updateFactory);

    /**
     * @param Request $request
     * @param UpdateResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response;
}
