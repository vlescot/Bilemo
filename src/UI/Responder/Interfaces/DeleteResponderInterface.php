<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DeleteResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface DeleteResponderInterface
{
    /**
     * @return Response
     */
    public function __invoke(): Response;
}
