<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface UpdatePhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface UpdatePhoneInterface
{
    /**
     * UpdatePhoneInterface constructor.
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
