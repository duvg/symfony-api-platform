<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return User::class;
    }

    // Find user by id
    public function findOneById(string $id): User
    {
        if (null === $user = $this->objectRepository->find($id)) {
            throw UserNotFoundException::fromUserId($id);
        }

        return $user;
    }

    // Search user by email
    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    /**
     * Save a user Entity.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * Remove user entity.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }

    public function findOneInactiveByIdAndTokenOrFail(string $id, string $token): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'token' => $token, 'active' => false])) {
            throw UserNotFoundException::fromUserIdAndToken($id, $token);
        }

        return $user;
    }

    public function findOneByIdAndResetPasswordToken(string $id, string $resetPasswordToken): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'resetPasswordToken' => $resetPasswordToken])) {
            throw UserNotFoundException::fromUserIdAndResetPasswordToken($id, $resetPasswordToken);
        }

        return $user;
    }
}
