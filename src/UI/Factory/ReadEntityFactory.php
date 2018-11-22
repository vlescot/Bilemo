<?php
declare(strict_types=1);

namespace App\UI\Factory;

use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use App\Domain\Entity\User;
use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use App\UI\Responder\Interfaces\CacheReadResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ReadEntityFactory
 * @package App\UI\Factory
 */
final class ReadEntityFactory implements ReadEntityFactoryInterface
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var CacheReadResponderInterface
     */
    private $responder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        CacheReadResponderInterface $responder
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->responder = $responder;
    }

    /**
     * {@inheritdoc}
     */
    public function read(Request $request, string $entityName)
    {
        $repository = $this->em->getRepository($entityName);
        $entityId = $request->attributes->get('id');

        $phoneLastUpdateDate = $repository->getOneUpdateDate($entityId);

        if (!$phoneLastUpdateDate) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $entityId));
        }

        $this->responder->buildCache(intval($phoneLastUpdateDate));

        if ($this->responder->isCacheValid($request)) {
            return $this->responder->getResponse();
        }

        $phone = $repository->findOneById($entityId);

        return $this->responder->createResponse($phone, self::ENTITY_STRING[$entityName]);
    }
}
