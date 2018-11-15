<?php
declare(strict_types=1);

namespace App\UI\Factory\Interfaces;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ReadEntityFactoryInterface
 * @package App\UI\Factory\Interfaces
 */
interface ReadEntityFactoryInterface
{
    /**
     * ReadEntityFactoryInterface constructor.
     *
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer
    );

    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return mixed
     */
    public function read(Request $request, string $entityName);
}
