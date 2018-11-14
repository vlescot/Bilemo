<?php
declare(strict_types=1);

namespace App\UI\Action\Login;

use App\UI\Action\Login\Interfaces\LoginInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/token/user",
 *     name="user_token",
 *     methods={"POST"}
 * )
 *
 * Class UserLoginAction
 * @package App\UI\Action\User
 */
final class UserLogin implements LoginInterface
{
    public function __invoke()
    {
        // Supported by Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator
    }
}
