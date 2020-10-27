<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class RequestResetPasswordActionTest extends UserTestBase
{
    public function testRequestResetPassword(): void
    {
        $payload = ['email' => 'yamid@api.com'];

        self::$yamid->request(
            'POST',
            \sprintf('%s/request_reset_password', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testRequestResetPasswordForNonExistingEmail(): void
    {
        $payload = ['email' => 'non-exixting@api.com'];

        self::$yamid->request(
            'POST',
            \sprintf('%s/request_reset_password', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());

    }
}