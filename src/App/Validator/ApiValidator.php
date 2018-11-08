<?php
declare(strict_types=1);

namespace App\App\Validator;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ApiValidator
 * @package App\App\Validator
 */
final class ApiValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Validator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $value
     * @param null $constraints
     * @param null $groups
     */
    public function validate($value, $constraints = null, $groups = null)
    {
        $violations = $this->validator->validate($value, $constraints, $groups);

        if (\count($violations) > 0) {
            $data = [];
            foreach ($violations as $violation) {
                $data[$violation->getPropertyPath()] = $violation->getMessage();
            }

            $apiError = new ApiError(Response::HTTP_BAD_REQUEST, ApiError::TYPE_VALIDATION_ERROR);
            $apiError->set('validation_error(s)', $data);

            throw new ApiException($apiError);
        }
    }
}
