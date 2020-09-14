<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return UserInterface
     *
     */
    public function loadUserByUsername(string $username)
    {
        $user = $this->userRepository->findByNickname($username);

        if($user === null) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        $newUser = $this->userRepository->findByNickname($user->getUsername());

        if ($newUser === null) {
            throw new UsernameNotFoundException();
        }

        return $newUser;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class)
    {
        return User::class === $class;
    }
}