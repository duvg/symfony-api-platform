<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exceptions\User\UserNotFoundException;
use App\Service\User\ActivateAccountService;
use Symfony\Component\Uid\Uuid;

class ActivateAccountServiceTest extends UserServiceTestBase
{
    private ActivateAccountService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ActivateAccountService($this->userRepository);
    }

    public function testActivateAccount(): void
    {
        $user = new User('user', 'user@api.com');
        $id = Uuid::v4()->toRfc4122();
        $token = \sha1(\uniqid());

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willReturn($user);

        $user = $this->service->activate($id, $token);

        # check result instanceof User class
        $this->assertInstanceOf(User::class, $user);
        # check token to null
        $this->assertNull($user->getToken());
        # check if user ios active
        $this->assertTrue($user->isActive());
    }


    public function testForNonExistingUser(): void
    {
        $user = new User('user', 'user@api.com');
        $id = Uuid::v4()->toRfc4122();
        $token = \sha1(\uniqid());

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willThrowException(new UserNotFoundException(\sprintf('User with id %s and token %s not found', $id, $token)));

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('User with id %s and token %s not found', $id, $token));

        $this->service->activate($id, $token);
    }
}
