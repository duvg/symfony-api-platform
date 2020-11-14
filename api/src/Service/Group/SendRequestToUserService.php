<?php

declare(strict_types=1);

namespace App\Service\Group;


use App\Entity\GroupRequest;
use App\Exception\Group\NotOwnerOfGroupException;
use App\Exception\Group\UserAlreadyMemberOfGroupException;
use App\Messenger\Message\GroupRequestMessage;
use App\Messenger\RoutingKey;
use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class SendRequestToUserService
{
    private UserRepository $userRepository;
    private GroupRepository $groupRepository;
    private GroupRequestRepository $repository;
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, GroupRepository $groupRepository, GroupRequestRepository  $repository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->repository = $repository;
        $this->messageBus = $messageBus;
    }

    public function send(string $groupId, string $email, string $requestedId): void
    {
        $group = $this->groupRepository->findOneByIdOrFail($groupId);
        $requester = $this->userRepository->findOneById($requestedId);
        $receiver = $this->userRepository->findOneByEmailOrFail($email);

        if (!$group->isOwnedBy($requester)) {
            throw new NotOwnerOfGroupException();
        }

        if($group->containsUser($receiver)) {
            throw new UserAlreadyMemberOfGroupException();
        }

        $groupRequest = new GroupRequest($group, $receiver);

        $this->groupRepository->saveEntity($groupRequest);

        $this->messageBus->dispatch(
            new GroupRequestMessage(
                $groupId,
                $receiver->getId(),
                $groupRequest->getToken(),
                $requester->getName(),
                $group->getName(),
                $receiver->getEmail()
            ),
            [new AmqpStamp(RoutingKey::GROUP_QUEUE)]
        );
    }
}