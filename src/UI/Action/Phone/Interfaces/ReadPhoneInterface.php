<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadPhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface ReadPhoneInterface
{
    /**
     * ReadPhoneActionInterface constructor.
     *
     * @param PhoneRepository $phoneRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer
    );

    /**
     * @param Request $request
     * @param ReadResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, ReadResponderInterface $responder): Response;
}
