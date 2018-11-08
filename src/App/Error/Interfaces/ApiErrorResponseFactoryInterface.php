<?php
declare(strict_types=1);

namespace App\App\Error\Interfaces;

use App\App\Error\ApiError;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ApiErrorFactoryInterface
 * @package App\App\Error\Interfaces
 */
interface ApiErrorResponseFactoryInterface
{
    /**
     * @param ApiError $apiError
     *
     * @return Response
     */
    public function createResponse(ApiError $apiError): Response;
}
