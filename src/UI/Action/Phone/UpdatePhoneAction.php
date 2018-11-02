<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\ApiValidator;
use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Entity\Phone;
use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Phone\CreatePhoneResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/phone/{brand}-{model}",
 *     name="phone_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdatePhoneAction
 * @package App\UI\Action\Phone
 */
final class UpdatePhoneAction
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ApiValidator
     */
    private $apiValidator;

    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * UpdatePhoneAction constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidator $apiValidator
     * @param PhoneRepository $phoneRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidator $apiValidator,
        PhoneRepository $phoneRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->phoneRepository = $phoneRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     */
    public function __invoke(Request $request)
    {
        /*
         * TODO
         * Deserializer json en Phone
         * Repo->GetPhone(request->attribute)
         * Phone->update()Phone
         * Validation
         * Repo->save
         * Response
         */

    }
}