<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Entity\Phone;
use App\UI\Action\Phone\Interfaces\DeletePhoneInterface;
use App\UI\Factory\Interfaces\DeleteEntityFactoryInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_delete",
 *     methods={"DELETE"}
 * )
 *
 * Class DeletePhone
 * @package App\UI\Action\Phone
 */
final class DeletePhone implements DeletePhoneInterface
{
    /**
     * @var DeleteEntityFactoryInterface
     */
    private $deleteFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(DeleteEntityFactoryInterface $deleteFactory)
    {
        $this->deleteFactory = $deleteFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response
    {
        $this->deleteFactory->delete($request, Phone::class);

        return $responder();
    }
}
