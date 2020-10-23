<?php

declare(strict_types=1);

namespace App\Service\User;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Logic for activate user account
    public function activate(Request  $request, string $id): User
    {
        // Find user by id and token in request
        $user = $this->userRepository->findOneInactiveByIdAndTokenOrFail(
            $id,
            RequestService::getField($request, 'token')
        );

        // set activate to true and token to null
        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}