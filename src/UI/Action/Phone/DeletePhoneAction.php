<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/phone/{brand}-{model}",
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
    public function __invoke(Request $request)
    {
        $brand = $request->attributes->get('brand');
        $model = $request->attributes->get('model');

        $phone = $this->phoneRepository->findOneBy([
            'brand' => $brand,
            'model' => $model
        ]);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf('Resource not found with brand "%s" and model "%s"', $brand, $model));
        }

        $this->phoneRepository->remove($phone);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}