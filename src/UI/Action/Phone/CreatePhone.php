<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Entity\Phone;
use App\UI\Action\Phone\Interfaces\CreatePhoneInterface;
use App\UI\Factory\Interfaces\CreateEntityFactoryInterface;
use App\UI\Responder\Interfaces\CreateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/phones",
 *     name="phone_create",
 *     methods={"POST"}
 * )
 *
 * Class CreatePhoneAction
 * @package App\UI\Action\Phone
 */
final class CreatePhone implements CreatePhoneInterface
{
    /**
     * @var CreateEntityFactoryInterface
     */
    private $createFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(CreateEntityFactoryInterface $createFactory)
    {
        $this->createFactory = $createFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, CreateResponderInterface $responder): Response
    {
        $phone = $this->createFactory->create($request, Phone::class);

        return $responder($phone, 'phone', 'phone_read');
    }
}
