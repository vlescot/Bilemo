<?php
declare(strict_types=1);

namespace App\App\Security;

use App\App\Error\ApiError;
use App\App\Error\ApiException;
use App\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function supports(array $attributes, $subject)
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
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (!$this->supports($attributes, $subject)) {
            return false;
        }

        $username = $subject->attributes->get('username');

        $querierUser = $token->getUser();
        $queringUser = $this->userRepository->loadUserByUsername($username);

        if (!$queringUser) {
            return false;
        }
        dump('voter');
        return $this->allowAccess($querierUser, $queringUser);
    }

    /**
     * @param UserInterface $querierUser
     * @param UserInterface $queringUser
     *
     * @return int
     */
    private function allowAccess(UserInterface $querierUser, UserInterface $queringUser)
    {
        if ($queringUser->isEqualTo($querierUser)) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
