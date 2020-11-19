<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;


use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteMovementTest extends MovementTestBase
{
    public function testDeleteMovement(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getYamidMovementId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAnotherUserMovement(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getCarlosMovementId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}