<?php
declare(strict_types=1);

namespace App\App\Error;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiErrorResponseFactory
 * @package App\App\Error
 */
final class ApiErrorResponseFactory
{
    /**
     * @param ApiError $apiError
     *
     * @return JsonResponse
     */
    public function createResponse(ApiError $apiError): Response
    {
        $data = $apiError->toArray();

        $response = new JsonResponse( //TODO JsonResponse or Response ???
            $data,
            $apiError->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }
}
