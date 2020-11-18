<?php

declare(strict_types=1);

namespace App\Tests\Functional\Category;


use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteCategoryTest extends CategoryTestBase
{
    public function testDeleteCategory(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getYamidExpenseCategoryId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteCategoryFromGroup(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getYamidGroupExpenseCategoryId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteCategoryForAnotherUser(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getCarlosExpenseCategoryId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeleteCategoryForAnotherUserGroup(): void
    {
        self::$yamid->request('DELETE', \sprintf('%s/%s', $this->endpoint, $this->getCarlosGroupExpenseCategoryId()));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

}