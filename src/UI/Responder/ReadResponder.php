<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ReadResponder implements ReadResponderInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @{@inheritdoc}
     */
    public function __invoke($object, string $serializationGroup): Response
    {
        $json = $this->serializer->serialize($object, 'json', ['groups' => [$serializationGroup]]);

        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/hal+json']);
    }
}
