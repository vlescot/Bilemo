<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface CreatePhoneActionInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface CreatePhoneActionInterface
{
    /**
     * CreatePhoneActionInterface constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidatorInterface $apiValidator
     * @param PhoneRepository $phoneRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidatorInterface $apiValidator,
        PhoneRepository $phoneRepository,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param Request $request
     * @param CreateResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response;
}
