<?php
declare(strict_types=1);

namespace App\UI\Action\Phone;

use App\App\Pagination\Interfaces\PaginationFactoryInterface;
use App\Domain\Repository\PhoneRepository;
use App\UI\Action\Phone\Interfaces\ReadPhoneListActionInterface;
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
 * Class ReadPhoneListAction
 * @package App\UI\Action
 */
final class ReadPhoneListAction implements ReadPhoneListActionInterface
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
        // TODO Find a way to cache Phones list. The request accepts parameters (page and filters), given a changing content

//        $timestamp = $this->phoneRepository->getLastUpdateDate();
//
//        $lastModified = new \DateTime();
//        $lastModified->setTimestamp(intval($timestamp));
//
//        $response = new Response();
//        $response->setLastModified($lastModified);
//        $response->setPublic();
//
//        if ($response->isNotModified($request)) {
//            return $response;
//        }

        $route = $request->attributes->get('_route');

        $paginatedCollection = $this->paginationFactory->createCollection(
            $this->phoneRepository,
            $request,
            $route
        );

        $json = $this->serializer->serialize($paginatedCollection, 'json', ['groups' => ['phones_list']]);


        // TODO header-> 'Content-type'=> application/hal+json
        // Pour chacune des routes avec des liens hal
        return $response = $responder($json);
    }
}
