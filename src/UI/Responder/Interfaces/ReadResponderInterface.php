<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface ReadResponderInterface
{
    /**
     * ReadResponderInterface constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer);

    /**
     * @param $object
     * @param string $serializationGroup
     *
     * @return Response
     */
    public function __invoke($object, string $serializationGroup): Response;
}
