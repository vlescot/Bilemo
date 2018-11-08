<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use App\Domain\DTO\PhoneDTO;
use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\UpdatePhoneActionInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use App\UI\Responder\UpdateResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdatePhoneAction
 * @package App\UI\Action\Phone
 */
final class UpdatePhoneAction implements UpdatePhoneActionInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ApiValidatorInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidatorInterface $apiValidator,
        PhoneRepository $phoneRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->serializer = $serializer;
        $this->apiValidator = $apiValidator;
        $this->phoneRepository = $phoneRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response
    {
        $json = $request->getContent();

        $phoneId = $request->attributes->get('id');

        $phone = $this->phoneRepository->findOneById($phoneId);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s" and model "%s"', 'Phone', $phoneId));
        }

        try {
            $phoneDTO = $this->serializer->deserialize($json, PhoneDTO::class, 'json');
        } catch (NotEncodableValueException $e) {
            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiException($apiError);
        }

        $phone->update(
            $phoneDTO->description,
            $phoneDTO->price,
            $phoneDTO->stock
        );

        $this->apiValidator->validate($phone, null, ['phone']);

        $this->phoneRepository->save($phone);

        $jsonPhone = $this->serializer->serialize($phone, 'json', ['groups' => ['phone']]);

        return $responder($jsonPhone, $phone);
    }
}
