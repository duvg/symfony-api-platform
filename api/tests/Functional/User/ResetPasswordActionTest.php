<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordActionTest extends UserTestBase
{
    public function testResetPassword(): void
    {
        $yamidId = $this->getYamidId();

        $payload = [
            'resetPasswordToken' => '123456',
            'password' => 'new-password'
        ];

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s/reset_password', $this->endpoint, $yamidId),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($yamidId, $responseData['id']);
    }
}