<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserGroupsTest extends UserTestBase
{
    public function testGetUserGroups(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/groups', $this->endpoint, $this->getYamidId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

         $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
         $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetAnotherUserGroups(): void
    {
        self::$carlos->request('GET', \sprintf('%s/%s/groups', $this->endpoint, $this->getYamidId()));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('You can\'t retrieve another user groups', $responseData['message']);
    }
}