<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface CacheReadResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface CacheReadResponderInterface
{

    /**
     * CacheReadResponderInterface constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer);

    /**
     * @param int $timestamp
     */
    public function buildCache(int $timestamp): void;

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function isCacheValid(Request $request): bool;

    /**
     * @return Response
     */
    public function getResponse(): Response;

    /**
     * @param $object
     * @param string $validationGroup
     *
     * @return Response
     */
    public function createResponse($object, string $validationGroup): Response;
}
