<?php
declare(strict_types=1);

namespace App\App\Security;

use App\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter
 * @package App\App\Security
 */
final class UserVoter implements VoterInterface
{
    const ROLES = [
        'ROLE_SELF_USER',
    ];

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * UserVoter constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
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
        $queringUser = $this->userRepository->findOneById($id);

        if (!$queringUser) {
            return false;
        }

        return $this->allowAccess($querierUser, $queringUser);
    }

    /**
     * @param UserInterface $querierUser
     * @param UserInterface $queringUser
     *
     * @return int
     */
    private function allowAccess(UserInterface $querierUser, UserInterface $queringUser): int
    {
        if ($queringUser->isEqualTo($querierUser)) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
