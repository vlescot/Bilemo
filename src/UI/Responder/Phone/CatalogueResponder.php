<?php
declare(strict_types=1);

namespace App\UI\Responder\Phone;

use App\App\Pagination\PaginatedCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class CatalogueResponder
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    private $normalizer;

    /**
     * CatalogueResponder constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, ObjectNormalizer $normalizer)
    {
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
    }

    /**
     * @param PaginatedCollection $paginatedCollection
     *
     * @return Response
     */
    public function __invoke(PaginatedCollection $paginatedCollection)
    {
        // TODO serialization with groups => Normalize && serialize phones ?? DTO
        $json = $this->serializer->serialize(
            $paginatedCollection,
            'json'
//            ,
//            ['groups' => ['phone']]
        );

        return new Response($json, Response::HTTP_OK);
    }
}
