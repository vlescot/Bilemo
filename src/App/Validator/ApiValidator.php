<?php
declare(strict_types=1);

namespace App\App\Validator;

use App\App\ErrorException\ApiError;
use App\App\ErrorException\ApiException;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ApiValidator
 * @package App\App\Validator
 */
final class ApiValidator implements ApiValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
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
