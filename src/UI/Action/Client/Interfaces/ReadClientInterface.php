<?php
declare(strict_types=1);

namespace App\UI\Action\Client\Interfaces;

use App\UI\Factory\Interfaces\ReadEntityFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ReadClientInterface
{
    /**
     * ReadClientInterface constructor.
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
