<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;


use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AcceptRequestTest extends GroupTestBase
{
    public function testAcceptRequest(): void
    {
        $payload = [
            'userId' => $this->getCarlosId(),
            'token' => '234567'
        ];

        self::$carlos->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getYamidGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('The user has been added to the group', $responseData['message']);
    }

    public function testAcceptAnAlreadyAcceptedRequest(): void
    {
        $this->testAcceptRequest();

        $payload = [
            'userId' => $this->getCarlosId(),
            'token' => '234567'
        ];

        self::$carlos->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getYamidGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$carlos->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(GroupRequestNotFoundException::class, $responseData['class']);
    }
}