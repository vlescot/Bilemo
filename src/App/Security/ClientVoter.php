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
final class ClientVoter implements VoterInterface
{
    const ROLES = [
        'ROLE_SELF_CLIENT',
    ];

    /**
     * @var ClientRepository
     */
    private $userRepository;


    /**
     * ClientVoter constructor.
     *
     * @param ClientRepository $userRepository
     */
    public function __construct(ClientRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        return \count(array_intersect($attributes, self::ROLES)) > 0;
    }

    /**
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     *
     * @return bool|int
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (!$this->supports($attributes, $subject)) {
            return false;
        }

        $id = $subject->attributes->get('id');

        $querierUser = $token->getUser();
        $requestedUser = $this->userRepository->findOneById($id);

        if (!$requestedUser) {
            return false;
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
