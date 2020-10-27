<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterActionTest extends UserTestBase
{
    public function testRegister(): void
    {
        $payload = [
            'name' => 'Scoby',
            'email' => 'scoby@api.com',
            'password' => '123456'
        ];

        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['email'], $responseData['email']);
    }

    public function testRegisterWithMissingParameter(): void
    {
        $payload = [
            'name' => 'scoby',
            'password' => '123456',
        ];

        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRegisterWithInvalidPassword(): void
    {
        $payload = [
            'name' => 'scoby',
            'email' => 'scoby@api.com',
            'password' => '1',
        ];

        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}