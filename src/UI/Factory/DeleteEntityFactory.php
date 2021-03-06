<?php
declare(strict_types=1);

namespace App\UI\Factory;

use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use App\Domain\Entity\User;
use App\UI\Factory\Interfaces\DeleteEntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DeleteEntityFactory
 * @package App\UI\Factory
 */
final class DeleteEntityFactory implements DeleteEntityFactoryInterface
{
    private const ENTITY_STRING = [
        Phone::class => 'phone',
        Client::class => 'client',
        User::class => 'user'
    ];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Request $request, string $entityName)
    {
        $repository = $this->em->getRepository($entityName);
        $entityId = $request->attributes->get('id');

        if (!$repository->remove($entityId)) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', self::ENTITY_STRING[$entityName], $entityId));
        }
    }
}
