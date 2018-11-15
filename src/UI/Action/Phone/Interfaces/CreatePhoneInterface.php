<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface CreatePhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface CreatePhoneInterface
{
    /**
     * CreatePhoneInterface constructor.
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
