<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\Pagination\PaginationFactory;
use App\Domain\Repository\PhoneRepository;
use App\UI\Responder\Phone\CatalogueResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/phone",
 *     name="phones_list",
 *     methods={"GET"}
 * )
 *
 * Class ReadPhoneListAction
 * @package App\UI\Action
 */
final class ReadPhoneListAction
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * @var PaginationFactory
     */
    private $paginationFactory;

    /**
     * ReadCatalogueAction constructor.
     *
     * @param PhoneRepository $phoneRepository
     * @param PaginationFactory $paginationFactory
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        PaginationFactory $paginationFactory
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->paginationFactory = $paginationFactory;
    }


    /**
     * @param Request $request
     * @param CatalogueResponder $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CatalogueResponder $responder): Response
    {
        $route = $request->attributes->get('_route');

        // TODO Inserer des liens vers chaque Phone
        $phones = $this->paginationFactory->createCollection(
            $this->phoneRepository,
            $request,
            $route
        );

        return $responder($phones);
    }
}
