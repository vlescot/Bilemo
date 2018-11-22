<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\CacheReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class CacheReadResponder implements CacheReadResponderInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Response
     */
    private $response;

    /**
     * {@inheritdoc}
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCache(int $timestamp): void
    {
        $lastModifiedTime = new \DateTime();
        $lastModifiedTime->setTimestamp(intval($timestamp));

        $response = new Response();
        $response->setLastModified($lastModifiedTime);
        $this->response = $response->setPublic();
    }

    /**
     * {@inheritdoc}
     */
    public function isCacheValid(Request $request): bool
    {
        return $this->response->isNotModified($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse($object, string $validationGroup): Response
    {
        if (!\is_object($object) && !\is_object($object[0])) {
            throw new \InvalidArgumentException('The CacheReadResponder need a object(s) to serialize');
        }

        $json  = $this->serializer->serialize($object, 'json', ['groups' => [$validationGroup]]);

        $this->response->setStatusCode(200);
        $this->response->headers->set('Content-Type', 'application/hal+json');
        return $this->response->setContent($json);
    }
}
