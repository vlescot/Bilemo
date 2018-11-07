<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\Normalizer\ApiNormalizer;
use App\Domain\Repository\PhoneRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_read",
 *     methods={"GET"}
 * )
 *
 * Class PhoneAction
 * @package App\UI\Action
 */
final class ReadPhoneAction
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * ReadPhoneAction constructor.
     *
     * @param PhoneRepository $phoneRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $phoneId = $request->attributes->get('id');

        $phone = $this->phoneRepository->findOneById($phoneId);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $phoneId));
        }

        $json = $this->serializer->serialize($phone, 'json',['groups' => ['phone']]);

        return new Response($json, Response::HTTP_OK);
    }
}
