<?php
declare(strict_types=1);

namespace App\UI\Action\Phone\Interfaces;

use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ReadPhoneInterface
 * @package App\UI\Action\Phone\Interfaces
 */
interface ReadPhoneInterface
{
    /**
     * ReadPhoneInterface constructor.
     *
     * @param ReadEntityFactoryInterface $readFactory
     */
    public function __construct(ReadEntityFactoryInterface $readFactory);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
