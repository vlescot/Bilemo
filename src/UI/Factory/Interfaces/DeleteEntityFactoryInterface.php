<?php
declare(strict_types=1);

namespace App\UI\Factory\Interfaces;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface DeleteEntityFactoryInterface
 * @package App\UI\Factory\Interfaces
 */
interface DeleteEntityFactoryInterface
{
    /**
     * DeleteEntityFactoryInterface constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em);

    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return mixed
     */
    public function delete(Request $request, string $entityName);
}
