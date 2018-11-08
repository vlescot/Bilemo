<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\DeleteResponder;
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
final class DeletePhoneAction
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * DeletePhoneAction constructor.
     *
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request, DeleteResponder $responder): Response
    {
        $phoneId = $request->attributes->get('id');

        $phone = $this->phoneRepository->findOneById($phoneId);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf('Resource %s not found with brand "%s"', 'Phone', $phoneId));
        }

        $this->phoneRepository->remove($phone);

        return $responder();
    }
}
