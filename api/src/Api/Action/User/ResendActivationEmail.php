<?php

declare(strict_types=1);

namespace App\Api\Action\User;

use App\Service\Request\RequestService;
use App\Service\User\ResendActivationEmailService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResendActivationEmail
{
    private ResendActivationEmailService $resendActivationEmailService;

    public function __construct(ResendActivationEmailService $resendActivationEmailService)
    {
        $this->resendActivationEmailService = $resendActivationEmailService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->resendActivationEmailService->resend(RequestService::getField($request, 'email'));

        return new JsonResponse(['message' => 'Activation email sent']);
    }
}
