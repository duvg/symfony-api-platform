<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupUserTest extends GroupTestBase
{
    public function testGetGroupUsers(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/users', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetAnotherGroupUsers(): void
    {
        self::$carlos->request('GET', \sprintf('%s/%s/users', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);


        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('You can\'t retrieve users another groups', $responseData['message']);
    }
}