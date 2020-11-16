<?php

declare(strict_types=1);

namespace App\Service\Group;


use App\Entity\User;
use App\Exception\Group\OwnerCannotBeDeletedException;
use App\Exception\Group\UserNotMemberOfGroupException;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class RemoveUserService
{
    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function remove(string $groupId, string $userId, User $requester): void
    {
        // Get group and user
        $group = $this->groupRepository->findOneByIdOrFail($groupId);
        $user = $this->userRepository->findOneByIdOrFail($userId);

        // If user is owner of group
        if ($user->equals($requester)) {
            throw new OwnerCannotBeDeletedException();
        }

        // If user is not member of group
        if (!$user->isMemberOfGroup($group)) {
            throw new UserNotMemberOfGroupException();
        }

        // Remove user from group and group from user
        $this->groupRepository->getEntityManager()->transactional(
            function(EntityManagerInterface $em) use ($group, $user) {
                $group->removeUser($user);
                $user->removeGroup($group);

                $em->persist($group);
            });

    }
}