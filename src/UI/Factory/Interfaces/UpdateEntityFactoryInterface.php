<?php
declare(strict_types=1);

namespace App\UI\Factory\Interfaces;

use App\App\ParametersBuilder\Interfaces\ParametersBuilderInterface;
use App\App\Validator\Interfaces\ApiValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface UpdateEntityFactoryInterface
 * @package App\UI\Factory\Interfaces
 */
interface UpdateEntityFactoryInterface
{
    /**
     * UpdateEntityFactoryInterface constructor.
     *
     * @param SerializerInterface $serializer
     * @param ApiValidatorInterface $apiValidator
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param ParametersBuilderInterface $parametersBuilder
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        SerializerInterface $serializer,
        ApiValidatorInterface $apiValidator,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        ParametersBuilderInterface $parametersBuilder,
        UserPasswordEncoderInterface $passwordEncoder
    );

    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return mixed
     */
    public function update(Request $request, string $entityName);
}
