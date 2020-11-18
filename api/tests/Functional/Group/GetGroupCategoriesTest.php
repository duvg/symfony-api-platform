<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupCategoriesTest extends GroupTestBase
{
    public function testGetGroupCategories(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/categories', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $responseData['hydra:member']);
    }

    public function getAnotherGroupCategories(): void
    {
        self::$carlos->request('GET', \sprintf('%s/%s/categories', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $responseData['hydra:member']);
    }
}