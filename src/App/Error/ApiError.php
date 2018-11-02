<?php
declare(strict_types=1);

namespace App\App\Error;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\AuthenticationEvents;

// TODO Vérifier si ApiError est pertinant ou peux être inclus dans ApiExcetion
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
    private $extraData = array();

    /**
     * ApiError constructor.
     *
     * @param int $statusCode
     * @param string $type
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
     * @return array
     */
    public function toArray()
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
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed|string
     */
    public function getMessage()
    {
        return$this->message;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }
}
