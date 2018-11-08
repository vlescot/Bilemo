<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;
use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DeletePhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface DeletePhoneActionInterface
{
    /**
     * DeletePhoneActionInterface constructor.
     *
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(PhoneRepository $phoneRepository);

    /**
     * @param Request $request
     * @param DeleteResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response;
}
