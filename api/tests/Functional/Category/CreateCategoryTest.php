<?php

declare(strict_types=1);

namespace App\Tests\Functional\Category;


use App\Entity\Category;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateCategoryTest extends CategoryTestBase
{
    public function testCreateCategory(): void
    {
        $payload = [
            'name' => "Yamid Expense Category",
            'type' => Category::EXPENSE,
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
        $this->assertEquals($payload['type'], $responseData['type']);
        $this->assertEquals($payload['owner'], $responseData['owner']);
    }

    public function testCreateCategoryForAnotherUser(): Void
    {
        $payload = [
            'name' => 'Carlos Expense Category',
            'type' => Category::INCOME,
            'owner' => \sprintf('/api/v1/users/%s', $this->getCarlosId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateCategoryForGroup(): void
    {
        $payload = [
            'name' => 'New category for group',
            'type' => Category::EXPENSE,
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'group' => \sprintf('/api/v1/groups/%s', $this->getYamidGroupId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
        $this->assertEquals($payload['type'], $responseData['type']);
        $this->assertEquals($payload['owner'], $responseData['owner']);
        $this->assertEquals($payload['group'], $responseData['group']);
    }

    public function testCreateCategoryForAnotherGroup(): void
    {
        $payload = [
            'name' => 'new category for group',
            'type' => Category::EXPENSE,
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId()),
            'group' => \sprintf('/api/v1/groups/%s', $this->getCarlosGroupId())
        ];

        self::$yamid->request('POST', $this->endpoint, [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateCategoryWithUnsupportedType(): void
    {
        $payload = [
            'name' => 'New category',
            'type' => 'invalid-type',
            'owner' => \sprintf('/api/v1/users/%s', $this->getYamidId())
        ];

        self::$yamid->request('POST', $this->endpoint, [] , [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

}