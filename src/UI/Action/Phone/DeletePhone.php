<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\DeletePhoneInterface;
use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/phones/{id}",
 *     name="phone_delete",
 *     methods={"DELETE"}
 * )
 *
 * Class DeletePhoneAction
 * @package App\UI\Action\Phone
 */
final class DeletePhone implements DeletePhoneInterface
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, DeleteResponderInterface $responder): Response
    {
        $id = $request->attributes->get('id');

        if (!$this->phoneRepository->remove($id)) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with id "%s"', 'Phone', $id));
        }

        return $responder();
    }
}
