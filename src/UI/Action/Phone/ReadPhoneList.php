<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\Pagination\Interfaces\PaginationFactoryInterface;
use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\ReadPhoneListInterface;
use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "/api/phones",
 *     name="phones_list",
 *     methods={"GET"}
 * )
 *
 * Class ReadPhoneList
 * @package App\UI\Action
 */
final class ReadPhoneList implements ReadPhoneListInterface
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * @var PaginationFactoryInterface
     */
    private $paginationFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        PaginationFactoryInterface $paginationFactory,
        SerializerInterface $serializer
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->paginationFactory = $paginationFactory;
        $this->serializer = $serializer;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, ReadResponderInterface $responder): Response
    {
        $route = $request->attributes->get('_route');

        $paginatedCollection = $this->paginationFactory->createCollection(
            $this->phoneRepository,
            $request,
            $route
        );

        return $responder($paginatedCollection, 'phones_list');
    }
}
