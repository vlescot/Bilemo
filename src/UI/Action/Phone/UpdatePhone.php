<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Entity\Phone;
use App\UI\Action\Phone\Interfaces\UpdatePhoneInterface;
use App\UI\Factory\Interfaces\UpdateEntityFactoryInterface;
use App\UI\Responder\Interfaces\UpdateResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdatePhone
 * @package App\UI\Action\Phone
 */
final class UpdatePhone implements UpdatePhoneInterface
{
    /**
     * @var UpdateEntityFactoryInterface
     */
    private $updateFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(UpdateEntityFactoryInterface $updateFactory)
    {
        $this->updateFactory = $updateFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, UpdateResponderInterface $responder): Response
    {
        $phone = $this->updateFactory->update($request, Phone::class);

        return $responder($phone, 'phone', 'phone_read');
    }
}
