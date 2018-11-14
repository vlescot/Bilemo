<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\DeleteResponderInterface;
use Symfony\Component\HttpFoundation\Response;

final class DeleteResponder implements DeleteResponderInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
