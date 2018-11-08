<?php
declare(strict_types=1);

namespace App\UI\Responder;

use Symfony\Component\HttpFoundation\Response;

final class DeleteResponder
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
