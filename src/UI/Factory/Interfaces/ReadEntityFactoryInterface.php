<?php
declare(strict_types=1);

namespace App\UI\Factory\Interfaces;

use App\UI\Responder\Interfaces\CacheReadResponderInterface;
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
     * @param CacheReadResponderInterface $responder
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        CacheReadResponderInterface $responder
    );

    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return mixed
     */
    public function read(Request $request, string $entityName);
}
