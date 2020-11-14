<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupTest extends GroupTestBase
{
     public function testGetGroup(): void
     {
         $yamidGroupId = $this->getYamidGroupId();

         self::$yamid->request('GET', \sprintf('%s/%s', $this->endpoint, $yamidGroupId));

         $response = self::$yamid->getResponse();
         $responseData = $this->getResponseData($response);

         $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
         $this->assertEquals($yamidGroupId, $responseData['id']);
     }

     public function testGetAnotherGroupData(): void
     {
         $yamidGroupId = $this->getYamidGroupId();

         self::$carlos->request('GET', \sprintf('%s/%s', $this->endpoint, $yamidGroupId));

         $response = self::$carlos->getResponse();

         $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
     }
}