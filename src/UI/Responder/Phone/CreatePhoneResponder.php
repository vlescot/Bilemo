<?php
declare(strict_types=1);

namespace App\UI\Responder\Phone;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreatePhoneResponder
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CreatePhoneResponder constructor.
     *
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param array $createdPhone
     *
     * @return Response
     */
    public function __invoke(array $createdPhone)
    {
        $createdUrl = $this->urlGenerator->generate('phone_read', $createdPhone);

        return new Response(null, Response::HTTP_CREATED, [
            'Location' => $createdUrl
        ]);
    }
}
