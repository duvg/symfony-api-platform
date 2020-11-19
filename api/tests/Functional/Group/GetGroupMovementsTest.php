<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupMovementsTest extends GroupTestBase
{
    public function testGetGroupMovements(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/movements', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }
    public function testGetAnotherGroupMovements(): void
    {
        self::$yamid->request('GET', \sprintf('%s/%s/movements', $this->endpoint, $this->getCarlosGroupId()));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $responseData['hydra:member']);
    }
}