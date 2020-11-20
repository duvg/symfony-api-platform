<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateMovementTest extends MovementTestBase
{
    public function testCreateMovement(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'amount' => 100.70,
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['category'], $responseData['category']['@id']);
        $this->assertEquals($payload['owner'], $responseData['owner']['@id']);
        $this->assertEquals($payload['amount'], $responseData['amount']);
    }

    public function testCreateMovementForAnotherUser(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getCarlosId()),
            'amount' => 200.30
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateMovementWithAnotherUserCategory(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getCarlosExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'amount' => 300.90
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateMovementForGroup(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidGroupExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'group' => \sprintf('/api/v1/groups/%s', $this->getYamidGroupId()),
            'amount' => 100.72
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['category'], $responseData['category']['@id']);
        $this->assertEquals($payload['owner'], $responseData['owner']['@id']);
        $this->assertEquals($payload['group'], $responseData['group']['@id']);
        $this->assertEquals($payload['amount'], $responseData['amount']);
    }

    public function testCreateMovementForAnotherUserGroup(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidGroupExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'group' => \sprintf('/api/v1/groups/%s', $this->getCarlosGroupId()),
            'amount' => 200.50
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateMovementForAnotherGroupCategory(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getCarlosGroupExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'group' => \sprintf('/api/v1/groups/%s', $this->getYamidGroupId()),
            'amount' => 300.40
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateMovementWithInvalidAmount(): void
    {
        $payload = [
            'category' => \sprintf('/api/v1/categories/%s', $this->getYamidExpenseCategoryId()),
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'amount' => 'invalid-value'
        ];

        self::$yamid->request(
            'POST',
            $this->endpoint,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());


    }
}