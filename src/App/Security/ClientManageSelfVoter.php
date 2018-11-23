<?php
declare(strict_types=1);

namespace App\App\Security;

use App\Domain\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ClientVoter
 * @package App\App\Security
 */
final class ClientManageSelfVoter implements VoterInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;


    /**
     * ClientVoter constructor.
     *
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param array $attributes
     * @param $subject
     *
     * @return bool
     */
    public function supports(array $attributes, $subject): bool
    {
        if (!$subject instanceof Request) {
            return false;
        }

        return \count(array_intersect($attributes, ['ROLE_SELF_CLIENT'])) > 0;
    }

    /**
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        if (!$this->supports($attributes, $subject)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $id = $subject->attributes->get('id');

        $querierUser = $token->getUser();
        $requestedUser = $this->clientRepository->findOneById($id);

        if (!$requestedUser) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        return $this->allowAccess($querierUser, $requestedUser);
    }

    /**
     * @param UserInterface $querierUser
     * @param UserInterface $requestedUser
     * @return int
     */
    private function allowAccess(UserInterface $querierUser, UserInterface $requestedUser): int
    {
        if ($querierUser->isEqualTo($requestedUser)) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
