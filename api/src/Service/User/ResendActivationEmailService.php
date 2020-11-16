<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Exception\User\UserIsActiveException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ResendActivationEmailService
{
    private UserRepository $userRepository;

    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    public function resend(string $email): void
    {
        // Find user by email
        $user = $this->userRepository->findOneByEmailOrFail($email);

        // throw exception if user already active
        if ($user->isActive()) {
            throw UserIsActiveException::fromEmail($email);
        }

        // set a new token
        $user->setToken(\sha1(\uniqid()));

        $this->messageBus->dispatch(
            new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
    }
}
