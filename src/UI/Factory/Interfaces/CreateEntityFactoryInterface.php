<?php
declare(strict_types=1);

namespace App\UI\Factory\Interfaces;

use App\App\ParametersBuilder\Interfaces\ParametersBuilderInterface;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface CreateEntityInterface
 * @package App\UI\Factory\Interfaces
 */
interface CreateEntityFactoryInterface
{
    /**
     * CreateEntityInterface constructor.
     *
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ApiValidatorInterface $apiValidator
     * @param EntityManagerInterface $em
     * @param ParametersBuilderInterface $parametersBuilder
     */
    public function __construct(
        SerializerInterface $serializer,
        UserPasswordEncoderInterface $passwordEncoder,
        ApiValidatorInterface $apiValidator,
        EntityManagerInterface $em,
        ParametersBuilderInterface $parametersBuilder
    ) ;

    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return mixed
     */
    public function create(Request $request, string $entityName);
}
