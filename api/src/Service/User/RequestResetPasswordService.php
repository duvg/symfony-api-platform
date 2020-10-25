<?php

declare(strict_types=1);

namespace App\Service\User;


use App\Messenger\Message\RequestResetPasswordMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class RequestResetPasswordService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param Request $request
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function send(Request $request): void
    {
        # Find user and set new reset password token
        $user = $this->userRepository->findOneByEmailOrFail(RequestService::getField($request, 'email'));
        $user->setResetPasswordToken(\sha1(uniqid()));

        # Save changes in entity
        $this->userRepository->save($user);

        # Send message
        $this->messageBus->dispatch(
            new RequestResetPasswordMessage($user->getId(), $user->getEmail(), $user->getResetPasswordToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
    }
}