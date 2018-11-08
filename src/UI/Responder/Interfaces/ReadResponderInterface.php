<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ReadResponderInterface
 * @package App\UI\Responder\Interfaces
 */
interface ReadResponderInterface
{
    /**
     * @param string $json
     *
     * @return Response
     */
    public function __invoke(string $json): Response;
}
