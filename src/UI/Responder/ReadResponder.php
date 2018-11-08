<?php
declare(strict_types=1);

namespace App\UI\Responder;

use Symfony\Component\HttpFoundation\Response;

final class ReadResponder
{
    /**
     * @param string $json
     *
     * @return Response
     */
    public function __invoke(string $json): Response
    {
        return new Response($json, Response::HTTP_OK);
    }
}
