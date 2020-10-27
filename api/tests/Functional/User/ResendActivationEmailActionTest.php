<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResendActivationEmailActionTest extends UserTestBase
{
    public function testResendActivationEmail(): void
    {
        $payload = ['email' => 'jose@api.com'];

        self::$jose->request(
            'POST',
            \sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$jose->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testResendActivationEmailToActiveUser(): void
    {
        $payload = [
            'email' => 'yamid@api.com'
        ];

        self::$yamid->request(
            'POST',
            \sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
    }
}