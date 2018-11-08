<?php
declare(strict_types=1);

namespace App\App\Error;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiError
 *
 * @package App\App\Error
 */
final class ApiError
{
    const TYPE_VALIDATION_ERROR = 'There was a validation error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'Invalid JSON format sent';
    const TYPE_INVALID_REQUEST_FILTER_PAGINATION = 'No item found with this parameters';

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $extraData = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(int $statusCode, string $type = null)
    {
        if (null === $type) {
            $type = 'about:blank';
            $message = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code';
        } else {
            if (!isset($type)) {
                throw new \InvalidArgumentException('No message for type '. $type);
            }
            $message = $type;
        }

        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return array_merge(
            [
                'status' => $this->statusCode,
                'message' => $this->message,
                'type' => $this->type,
            ],
            $this->extraData
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return$this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, $value)
    {
        $this->extraData[$name] = $value;
    }
}
