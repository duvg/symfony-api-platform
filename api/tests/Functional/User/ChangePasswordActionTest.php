<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class ChangePasswordActionTest extends UserTestBase
{
    public function testChangePassword(): void
    {
        $payload = [
            'oldPassword' => 'password',
            'newPassword' => 'new-password'
        ];
        $yamidId = $this->getYamidId();
        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s/change_password',$this->endpoint, $yamidId),
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

    public function testChangePasswordWithInvalidOldPassword(): void
    {
        $payload = [
            'oldPassword' => 'invalid-password',
            'newPassword' => 'password'
        ];

        $yamidId = $this->getYamidId();

        self::$yamid->request(
            'PUT',
            \sprintf('%s/%s/change_password', $this->endpoint, $yamidId),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());

    }
}