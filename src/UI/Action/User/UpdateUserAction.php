<?php
declare(strict_types=1);

namespace App\UI\Action\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(
 *     "/user/{username}",
 *     name="user_update",
 *     methods={"PUT"}
 * )
 *
 * Class UpdateUserAction
 * @package App\UI\Action\User
 */
final class UpdateUserAction
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * ReadUserAction constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        /*
        * TODO
        * Deserializer json en Phone
        * Repo->GetPhone(request->attribute)
        * Phone->update()Phone
        * Validation
        * Repo->save
        * Response
        */


        // TODO Vérifier si l'utisateur existe pour changer le msg d'erreur

        $this->authorizationChecker->isGranted('ROLE_SELF_USER');

        return new Response('ACCESS ALLOWED');


    }
}