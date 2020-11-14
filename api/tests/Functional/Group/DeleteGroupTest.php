<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteGroupTest extends GroupTestBase
{
    public function testDeleteGroup(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAnotherGroup(): void
    {
        self::$carlos->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getYamidGroupId()));

        $response = self::$carlos->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}