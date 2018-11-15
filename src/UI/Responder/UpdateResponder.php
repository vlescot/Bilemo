<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CreateUpdateResponder
 * @package App\UI\Responder
 */
final class UpdateResponder implements UpdateResponderInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($entity, string $serializationGroup, string $locationRouteName): Response
    {
        $json = $this->serializer->serialize($entity, 'json', ['groups' => [$serializationGroup] ]);

        return new Response($json, Response::HTTP_OK, [
            'location' => $this->urlGenerator->generate($locationRouteName, ['id' => $entity->getId()])
        ]);
    }
}
