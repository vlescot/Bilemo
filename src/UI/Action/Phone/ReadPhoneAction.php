<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Phone\ReadPhoneResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "phone/{brand}-{model}",
 *     name="phone_read",
 *     methods={"GET"}
 * )
 *
 * Class PhoneAction
 * @package App\UI\Action
 */
final class ReadPhoneAction
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * PhoneAction constructor.
     *
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    public function __invoke(Request $request, ReadPhoneResponder $responder)
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

        return $responder($phone);
    }
}
