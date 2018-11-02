<?php
declare(strict_types=1);

namespace App\UI\Responder\Phone;

use App\Domain\Entity\Phone;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ReadPhoneResponder
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * PhoneResponder constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(Phone $phone)
    {
        $json = $this->serializer->serialize($phone, 'json', ['groups' => ['phone']]);

        return new Response($json, Response::HTTP_OK);
    }
}
