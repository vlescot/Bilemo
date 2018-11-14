<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface CreateResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface CreateResponderInterface
{
    /**
     * CreateResponderInterface constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator);

    /**
     * @param string $json
     * @param $entity
     *
     * @return Response
     */
    public function __invoke(string $json, $entity): Response;
}
