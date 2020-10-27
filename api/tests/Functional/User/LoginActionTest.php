<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginActionTest extends UserTestBase
{
    public function testLogin(): void
    {
        $payload = [
            'username' => 'yamid@api.com',
            'password' => 'password'
        ];

        self::$yamid->request('POST', \sprintf('%s/login_check', $this->endpoint), [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testUserWithInvalidCredentials(): void
    {
        $payload = [
            'username' => 'yamid@api.com',
            'password' => 'invalid'
        ];

        self::$yamid->request('POST', \sprintf('%s/login_check', $this->endpoint), [], [], [], \json_encode($payload));

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertInstanceOf(JWTAuthenticationFailureResponse::class, $response);
    }
}