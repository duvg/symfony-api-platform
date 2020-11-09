<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUser(): void
    {
        $yamidId = $this->getYamidId();
        self::$yamid->request('GET', \sprintf('%s/%s', $this->endpoint, $yamidId));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($yamidId, $responseData['id']);
        $this->assertEquals('yamid@api.com', $responseData['email']);
    }

    public function testGetUserAnotherUserData(): void
    {
        $yamidId = $this->getYamidId();
        self::$carlos->request('GET', \sprintf('%s/%s', $this->endpoint, $yamidId));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}