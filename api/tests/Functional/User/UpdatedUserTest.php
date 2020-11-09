<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdatedUserTest extends UserTestBase
{
    public function testUpdateUser(): void
    {
        $payload = ['name' => 'Yamid New'];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidId()),
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

    public function testUpdateAnotherUser(): void
    {
        $payload = ['name' => 'Yamid New'];

        self::$carlos->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getYamidId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}