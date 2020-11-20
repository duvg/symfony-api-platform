<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DownloadFileTest extends MovementTestBase
{
    public function testDownloadFile(): void
    {
        // Create file in server
        $this->getContainer()->get('default.storage')->put('example.txt', 'Some random data!');

        $payload = ['filePath' => 'example.txt'];

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/download_file', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testDownloadAnotherUserFile(): void
    {
        // Create file in server
        $this->getContainer()->get('default.storage')->put('example.txt', 'Some random data!');

        $payload = ['filePath' => 'example.txt'];

        self::$carlos->request(
            'POST',
            \sprintf('%s/%s/download_file', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$carlos->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDownloadNonExistingFile(): void
    {
        // Create file in server
        $this->getContainer()->get('default.storage')->put('example.txt', 'Some random data!');

        $payload = ['filePath' => 'non-existing-file.txt'];

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/download_file', $this->endpoint, $this->getYamidMovementId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}