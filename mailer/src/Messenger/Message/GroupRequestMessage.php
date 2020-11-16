<?php

declare(strict_types=1);

namespace Mailer\Messenger\Message;


class GroupRequestMessage
{
    private string $groupId;
    private string $userId;
    private string $token;
    private string $requesterName;
    private string $groupName;
    private string $email;

    public function __construct(
        string $groupId,
        string $userId,
        string $token,
        string $requesterName,
        string $groupName,
        string $email
    ) {

        $this->groupId = $groupId;
        $this->userId = $userId;
        $this->token = $token;
        $this->requesterName = $requesterName;
        $this->groupName = $groupName;
        $this->email = $email;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getRequesterName(): string
    {
        return $this->requesterName;
    }

    public function setRequesterName(string $requesterName): void
    {
        $this->requesterName = $requesterName;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}