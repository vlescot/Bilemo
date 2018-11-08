<?php
declare(strict_types=1);

namespace App\App\Error;

use App\App\Error\Interfaces\ApiErrorResponseFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiErrorResponseFactory
 * @package App\App\Error
 */
final class ApiErrorResponseFactory implements ApiErrorResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResponse(ApiError $apiError): Response
    {
        $data = $apiError->toArray();

        $response = new JsonResponse($data, $apiError->getStatusCode());

        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }
}
