<?php

declare(strict_types=1);

namespace App\Tests\Functional\Category;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateCategoryTest extends CategoryTestBase
{
    public function testUpdateCategory(): void
    {
        $payload = ['name' => 'new name'];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidExpenseCategoryId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testUpdateGroupCategory(): void
    {
        $payload = ['name' => 'new category name'];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidGroupExpenseCategoryId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testUpdateCategoryForAnotherUser(): void
    {
        $payload = ['name' => 'category name',];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getCarlosExpenseCategoryId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateCategoryForAnotherUserGroup(): void
    {
        $payload = ['name' => 'New name'];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getCarlosGroupExpenseCategoryId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}