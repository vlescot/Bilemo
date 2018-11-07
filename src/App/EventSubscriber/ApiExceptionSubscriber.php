<?php
declare(strict_types=1);

namespace App\App\EventSubscriber;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\App\Error\ApiErrorResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $isAppDebug;

    /**
     * @var ApiErrorResponseFactory
     */
    private $responseFactory;

    /**
     * ApiExceptionSubscriber constructor.
     *
     * @param bool $isAppDebug
     * @param ApiErrorResponseFactory $responseFactory
     */
    public function __construct(
        bool $isAppDebug,
        ApiErrorResponseFactory $responseFactory
    ) {
        $this->isAppDebug = $isAppDebug;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        // Allow 500 errors to be thrown on dev environment
        if ($statusCode == 500 && $this->isAppDebug) {
            return;
        }

        if ($e instanceof ApiException) {
            $apiError = $e->getApiError();
        } else {
            $apiError = new ApiError($statusCode);

            if ($e instanceof HttpExceptionInterface) {
                $apiError->set('detail', $e->getMessage());
            }
        }

        $response = $this->responseFactory->createResponse($apiError);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}
