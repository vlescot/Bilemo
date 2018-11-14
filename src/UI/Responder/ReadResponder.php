<?php
declare(strict_types=1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\ReadResponderInterface;
use Symfony\Component\HttpFoundation\Response;

final class ReadResponder implements ReadResponderInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $json): Response
    {
        return new Response($json, Response::HTTP_OK);
    }
}
