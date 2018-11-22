<?php
declare(strict_types=1);

namespace App\UI\Action\User\Interfaces;

use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreateUserInterface
 * @package App\UI\Action\User\Interfaces
 */
interface CreateUserInterface
{
    /**
     * CreateUserInterface constructor.
     *
     * @param CreateEntityFactoryInterface $createFactory
     */
    public function __construct(CreateEntityFactoryInterface $createFactory);


    /**
     * @param Request $request
     * @param CreateResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response;
}
