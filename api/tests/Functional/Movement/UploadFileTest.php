<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;


use App\Api\Action\Movement\UploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadFileTest extends MovementTestBase
{
    public function testUploadFile(): void
    {
        $file = new UploadedFile(
          __DIR__.'/../../../fixtures/sofa.jpg',
            'sofa.jpg'
        );

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/upload_file', $this->endpoint, $this->getYamidGroupMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadFileWithWrongInputName(): void
    {
        $file = new UploadedFile(
            __DIR__.'/../../../fixtures/sofa.jpg',
            'sofa.jpg'
        );

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/upload_file', $this->endpoint, $this->getYamidGroupMovementId()),
            [],
            ['not-valid-input' => $file]
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}