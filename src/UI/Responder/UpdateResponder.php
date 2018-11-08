<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UpdateResponder
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CreateResponder constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $json
     * @param $entity
     *
     * @return Response
     */
    public function __invoke(string $json, $entity): Response
    {
        switch (get_class($entity)) {
            case Phone::class:
                $routeName = 'phone_read';
                break;
            case User::class:
                $routeName = 'user_read';
                break;
        }

        return new Response($json, Response::HTTP_OK, [
            'location' => $this->urlGenerator->generate($routeName, ['id' => $entity->getId()])
        ]);
    }
}
