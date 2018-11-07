<?php
declare(strict_types=1);

namespace App\App\Error;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiErrorResponseFactory
{
    /**
     * @param ApiError $apiError
     *
     * @return Response
     */
    public function createResponse(ApiError $apiError)
    {
        $data = $apiError->toArray();
        if ($data['type'] != 'about:blank') {
            $data['type'] = /*$this->baseURL*/'localhost' . '/docs/errors#' . $data['type'];     // TODO Faire de type une URL vers la doc
        }

        $response = new JsonResponse( //TODO JsonResponse or Response ???
            $data,
            $apiError->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }
}
