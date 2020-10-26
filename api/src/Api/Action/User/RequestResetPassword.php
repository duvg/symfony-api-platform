<?php

declare(strict_types=1);

namespace App\Api\Action\User;

use App\Service\Request\RequestService;
use App\Service\User\RequestResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPassword
{
    private RequestResetPasswordService $requestResetPasswordService;

    public function __construct(RequestResetPasswordService $requestResetPasswordService)
    {
        $this->requestResetPasswordService = $requestResetPasswordService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->requestResetPasswordService->send(RequestService::getField($request, 'email'));

        return new JsonResponse(['message' => 'Request reset password email sent']);
    }
}
