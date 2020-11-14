<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateGroupTest extends GroupTestBase
{
    public function testCreateGroup(): void
    {
        $payload = [
            'name' => 'My new group',
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testCreateGroupWithoutName(): void
    {
        $payload = [
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateGroupForAnotherUser(): void
    {
        $payload = [
            'name' => 'My new groups name',
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId())
        ];

        self::$carlos->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('You can not create groups for another user', $responseData['message']);
    }
}