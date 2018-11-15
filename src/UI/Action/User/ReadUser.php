<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use App\Domain\Entity\User;
use App\UI\Action\User\Interfaces\ReadUserInterface;
use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/users/{id}",
 *     name="user_read",
 *     methods={"GET"}
 * )
 *
 * Class ReadUserAction
 * @package App\UI\Action\User
 */
final class ReadUser implements ReadUserInterface
{
    /**
     * @var ReadEntityFactoryInterface
     */
    private $readFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(ReadEntityFactoryInterface $readFactory)
    {
        $this->readFactory = $readFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request): Response
    {
        return $this->readFactory->read($request, User::class);
    }
}
