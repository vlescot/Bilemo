<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\ReadPhoneActionInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
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
final class ReadPhoneAction implements ReadPhoneActionInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, ReadResponderInterface $responder): Response
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
