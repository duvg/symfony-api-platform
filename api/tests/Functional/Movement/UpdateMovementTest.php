<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateMovementTest extends MovementTestBase
{
    public function testUpdateMovement(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'amount' => 100.20,
        ];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['amount'], $responseData['amount']);
        $this->assertEquals($payload['category'], $responseData['category']['@id']);
    }

    public function testUpdateAnotherUserMovement(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'amount' => 100.20,
        ];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getCarlosMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateMovementWithAnotherUserCategory(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getCarlosExpenseCategoryId()),
            'amount' => 100.20,
        ];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }


    public function testUpdateMovementWithInvalidAmount(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'amount' => 'invalid-amount',
        ];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

}