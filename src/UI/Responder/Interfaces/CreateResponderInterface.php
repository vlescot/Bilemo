<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CreateResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface CreateResponderInterface
{
    /**
     * CreateResponderInterface constructor.
     *
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param $entity
     * @param string $serializationGroup
     * @param string $locationRouteName
     *
     * @return Response
     */
    public function __invoke($entity, string $serializationGroup, string $locationRouteName): Response;
}
