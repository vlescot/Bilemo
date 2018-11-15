<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\UI\Factory\Interfaces\DeleteEntityFactoryInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DeletePhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface DeletePhoneInterface
{
    /**
     * DeletePhoneInterface constructor.
     *
     * @param DeleteEntityFactoryInterface $deleteFactory
     */
    public function __construct(DeleteEntityFactoryInterface $deleteFactory);

    /**
     * @param Request $request
     * @param DeleteResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response;
}
