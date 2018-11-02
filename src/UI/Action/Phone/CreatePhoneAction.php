<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\ApiValidator;
use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Entity\Phone;
use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Phone\CreatePhoneResponder;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/phone",
 *     name="phone_create",
 *     methods={"POST"}
 * )
 *
 * Class CreatePhoneAction
 * @package App\UI\Action\Phone
 */
final class CreatePhoneAction
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
     * CreatePhoneHandler constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidator $apiValidator
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidator $apiValidator,
        PhoneRepository $phoneRepository
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * @param Request $request
     * @param CreatePhoneResponder $responder
     * @return Response
     */
    public function __invoke(Request $request, CreatePhoneResponder $responder)
    {
        $json = $request->getContent();

        try {
            $phone = $this->serializer->deserialize($json, Phone::class, 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $this->apiValidator->validate($phone, null, ['phone']);

        $this->phoneRepository->save($phone);

        return $responder([
            'brand' => $phone->getBrand(),
            'model' => $phone->getModel()
        ]);
    }
}
