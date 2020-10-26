<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Logic for activate user account

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function activate(string $id, string $token): User
    {
        // Find user by id and token in request
        $user = $this->userRepository->findOneInactiveByIdAndTokenOrFail($id, $token);

        // set activate to true and token to null
        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
