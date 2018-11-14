<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\ReadPhoneInterface;
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
final class ReadPhone implements ReadPhoneInterface
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

        $phoneLastUpdateDate = $this->phoneRepository->getOneUpdateDate($phoneId);

        if (!$phoneLastUpdateDate) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $phoneId));
        }

        $lastModified = new \DateTime();
        $lastModified->setTimestamp(intval($phoneLastUpdateDate));

        $response = new Response();
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }


        $phone = $this->phoneRepository->findOneById($phoneId);

        $json = $this->serializer->serialize($phone, 'json', ['groups' => ['phone']]);

        $response->setStatusCode(200);
        return $response->setContent($json);

//        return $responder($json);
    }
}
