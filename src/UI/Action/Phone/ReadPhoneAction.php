<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\ReadResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
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
     * @param ReadResponder $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, ReadResponder $responder): Response
    {
        $phoneId = $request->attributes->get('id');

        $phone = $this->phoneRepository->findOneById($phoneId);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $phoneId));
        }

        $json = $this->serializer->serialize($phone, 'json', ['groups' => ['phone']]);

        return $responder($json);
    }
}
