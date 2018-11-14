<?php
declare(strict_types=1);

namespace App\UI\Action\Login;

use App\UI\Action\Login\Interfaces\LoginInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/token/company",
 *     name="company_token",
 *     methods={"POST"}
 * )
 *
 * Class CompanyLoginAction
 * @package App\UI\Action
 */
final class CompanyLogin implements LoginInterface
{
    public function __invoke()
    {
        // Supported by Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator
    }
}
