<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadAvatarTest extends UserTestBase
{
    public function testUploadAvatar(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/sofa.jpg',
            'avatar.jpg'
        );

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/avatar', $this->endpoint, $this->getYamidId()),
            [],
            ['avatar' => $avatar]
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadAvatarWithWrongInputName(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/sofa.jpg',
            'avatar.jpg'
        );

        self::$yamid->request(
            'POST',
            \sprintf('%s/%s/avatar', $this->endpoint, $this->getYamidId()),
            [],
            ['non-valid-input' => $avatar]
        );

        $response = self::$yamid->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}