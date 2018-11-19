<?php
declare(strict_types=1);

namespace App\UI\Factory;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * {@inheritdoc}
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
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

        $lastModified = new \DateTime();
        $lastModified->setTimestamp(intval($phoneLastUpdateDate));

        $response = new Response();
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        $phone = $repository->findOneById($entityId);

        $json = $this->serializer->serialize($phone, 'json', ['groups' => [self::ENTITY_STRING[$entityName]]]);

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/hal+json');
        return $response->setContent($json);
    }
}
