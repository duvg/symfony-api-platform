<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserCategoryTest extends UserTestBase
{
    public function testGetUserCategories(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/categories', $this->endpoint, $this->getYamidId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount( 2, $responseData['hydra:member']);
    }

    public function testGetAnotherUserCategories(): void
    {
        self::$carlos->request('GET', \sprintf('%s/%s/categories', $this->endpoint, $this->getYamidId()));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $responseData['hydra:member']);
    }
}