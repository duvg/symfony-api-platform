<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class RemoveUserTest extends GroupTestBase
{
    public function testRemoveUserFromGroup(): void
    {
        $this->addUserToGroup();

        $payload = ['userId' => $this->getCarlosId()];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getYamidGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('The user has been removed!', $responseData['message']);
    }

    public function testRemoveTheOwner(): void
    {
        $payload = ['userId' => $this->getYamidId()];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getYamidGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals('Owner can not be deleted from a group. Try deleting the group instead.', $responseData['message']);
    }

    public function testRemoveNotAMember(): void
    {
        $payload = ['userId' => $this->getYamidId()];

        self::$carlos->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getCarlosGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('This user is not member of this group', $responseData['message']);
    }

    private function addUserToGroup(): void
    {
        $payload = [
            'userId' => $this->getCarlosId(),
            'token' => '234567',
        ];

        self::$carlos->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getYamidGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
    }
}