<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface UpdateUserActionInterface
 * @package App\UI\Action\User\Interfaces
 */
interface UpdateUserInterface
{
    /**
     * UpdateUserInterface constructor.
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
