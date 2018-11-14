<?php
declare(strict_types=1);

namespace App\App\ErrorException;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiException
 *
 * @package App\App\Error
 */
final class ApiException extends HttpException
{
    /**
     * @var ApiError
     */
    private $apiError;

    /**
     * ApiException constructor.
     *
     * @param ApiError $apiError
     * @param \Exception|null $previous
     */
    public function __construct(ApiError $apiError, \Exception $previous = null)
    {
        $this->apiError = $apiError;
        $statusCode = $apiError->getStatusCode();
        $message = $apiError->getMessage();

        parent::__construct($statusCode, $message, $previous);
    }

    /**
     * @return ApiError
     */
    public function getApiError(): ApiError
    {
        return $this->apiError;
    }
}
