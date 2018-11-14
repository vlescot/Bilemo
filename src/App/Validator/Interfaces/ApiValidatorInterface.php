<?php
declare(strict_types=1);

namespace App\App\Validator\Interfaces;

use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ApiValidatorInterface
{
    /**
     * ApiValidatorInterface constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator);

    /**
     * @param $value
     * @param null $constraints
     * @param null $groups
     *
     * @return mixed
     */
    public function validate($value, $constraints = null, $groups = null);
}
