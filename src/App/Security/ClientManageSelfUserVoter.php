<?php
declare(strict_types=1);

namespace App\App\Security;

use App\Domain\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ClientManageSelfUserVoter
 * @package App\App\Security
 */
final class ClientManageSelfUserVoter implements VoterInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * ClientManageSelfUserVoter constructor.
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
    public function supports(UserInterface $user, array $attributes, $subject): bool
    {
        if (!$subject instanceof Request) {
            return false;
        }

        return \count(array_intersect($user->getRoles(), ['ROLE_CLIENT'])) > 0
            && \count(array_intersect($attributes, ['ROLE_SELF_CLIENT'])) > 0;
    }

    /**
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $user = $token->getUser();

        if (!$this->supports($user, $attributes, $subject)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $userId = $subject->attributes->get('id');

        foreach ($user->getUsers()->getIterator() as $i => $user) {
            if ($userId === $user->getId()->toString()) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
