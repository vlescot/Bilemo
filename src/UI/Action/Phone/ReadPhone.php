<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Entity\Phone;
use App\UI\Action\Phone\Interfaces\ReadPhoneInterface;
use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_read",
 *     methods={"GET"}
 * )
 *
 * Class PhoneAction
 * @package App\UI\Action
 */
final class ReadPhone implements ReadPhoneInterface
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
        return $this->readFactory->read($request, Phone::class);
    }
}
